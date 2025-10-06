<?php
/**
 * location.php
 * หน้าเลือกสถานที่ทำงาน
 * - แสดงรายการสถานที่ที่มีอยู่ในระบบ
 * - ลิงก์ไปยังหน้า checklist ของแต่ละสถานที่
 */

// กำหนดค่าสำหรับ header template
$page_title = 'เลือกสถานที่ทำงาน';
$current_path = '../';
$extra_css = ['assets/location.css'];

// รวม header template
include __DIR__ . '/../includes/header.php';

// รายการสถานที่ทั้งหมด
$locations = [
    ['name' => 'เมืองสมุทรปราการ', 'description' => 'สำนักงานใหญ่ เมืองสมุทรปราการ', 'icon' => 'bi-building'],
    ['name' => 'พระประแดง', 'description' => 'สาขาพระประแดง', 'icon' => 'bi-geo-alt-fill'],
    ['name' => 'พระสมุทรเจดีย์', 'description' => 'สาขาพระสมุทรเจดีย์', 'icon' => 'bi-geo-alt-fill'],
    ['name' => 'บางพลี', 'description' => 'สาขาบางพลี', 'icon' => 'bi-geo-alt-fill'],
    ['name' => 'บางบ่อ', 'description' => 'สาขาบางบ่อ', 'icon' => 'bi-geo-alt-fill'],
    ['name' => 'บางเสาธง', 'description' => 'สาขาบางเสาธง', 'icon' => 'bi-geo-alt-fill']
];
?>

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-12">
            
            <!-- Header Card -->
            <div class="card shadow-lg mb-4 header-card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h2 class="card-title mb-0">
                                <i class="bi bi-geo-alt-fill me-2"></i>เลือกสถานที่ทำงาน
                            </h2>
                            <p class="subtitle mt-2 mb-0">เลือกสถานที่ที่ต้องการตรวจสอบรายการสินค้า</p>
                        </div>
                        <a href="../index.php" class="btn btn-light btn-sm">
                            <i class="bi bi-arrow-left me-1"></i>กลับหน้าหลัก
                        </a>
                    </div>
                </div>
            </div>

            <!-- Location Cards -->
            <div class="row g-4">
                <?php foreach ($locations as $index => $location): ?>
                    <div class="col-lg-4 col-md-6">
                        <div class="location-card" data-location="<?php echo htmlspecialchars($location['name']); ?>">
                            <div class="card-body text-center">
                                <div class="location-icon mb-3">
                                    <i class="<?php echo $location['icon']; ?>"></i>
                                </div>
                                <h5 class="location-title"><?php echo htmlspecialchars($location['name']); ?></h5>
                                <p class="location-description"><?php echo htmlspecialchars($location['description']); ?></p>
                                <div class="location-stats mb-3">
                                    <div class="stat-item">
                                        <span class="stat-number">8</span>
                                        <span class="stat-label">รายการสินค้า</span>
                                    </div>
                                </div>
                                <a href="checklist/checklist.php?location=<?php echo urlencode($location['name']); ?>" 
                                   class="btn btn-primary location-btn">
                                    <i class="bi bi-check2-square me-2"></i>เข้าสู่เช็คลิสต์
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Quick Stats Section -->
            <div class="row mt-5">
                <div class="col-12">
                    <div class="card shadow-lg stats-card">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="bi bi-bar-chart-fill me-2"></i>สถิติรวม
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row text-center">
                                <div class="col-md-3">
                                    <div class="stat-box">
                                        <div class="stat-icon">
                                            <i class="bi bi-geo-alt-fill"></i>
                                        </div>
                                        <div class="stat-number"><?php echo count($locations); ?></div>
                                        <div class="stat-label">สถานที่ทั้งหมด</div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="stat-box">
                                        <div class="stat-icon">
                                            <i class="bi bi-box-seam"></i>
                                        </div>
                                        <div class="stat-number">48</div>
                                        <div class="stat-label">รายการสินค้ารวม</div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="stat-box">
                                        <div class="stat-icon">
                                            <i class="bi bi-check-circle-fill"></i>
                                        </div>
                                        <div class="stat-number">32</div>
                                        <div class="stat-label">ตรวจสอบแล้ว</div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="stat-box">
                                        <div class="stat-icon">
                                            <i class="bi bi-clock-fill"></i>
                                        </div>
                                        <div class="stat-number">16</div>
                                        <div class="stat-label">รอตรวจสอบ</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
// กำหนด JavaScript สำหรับหน้านี้
$inline_js = '
    document.addEventListener("DOMContentLoaded", function() {
        // เอฟเฟ็กต์ hover สำหรับ location cards
        const locationCards = document.querySelectorAll(".location-card");
        locationCards.forEach(card => {
            card.addEventListener("mouseenter", function() {
                this.style.transform = "translateY(-5px)";
                this.style.boxShadow = "0 15px 35px rgba(0,0,0,0.1)";
            });
            
            card.addEventListener("mouseleave", function() {
                this.style.transform = "translateY(0)";
                this.style.boxShadow = "0 4px 15px rgba(0,0,0,0.08)";
            });
        });
        
        // เอฟเฟ็กต์ click ripple
        const buttons = document.querySelectorAll(".location-btn");
        buttons.forEach(button => {
            button.addEventListener("click", function(e) {
                const ripple = document.createElement("span");
                const rect = this.getBoundingClientRect();
                const size = Math.max(rect.width, rect.height);
                const x = e.clientX - rect.left - size / 2;
                const y = e.clientY - rect.top - size / 2;
                
                ripple.style.width = ripple.style.height = size + "px";
                ripple.style.left = x + "px";
                ripple.style.top = y + "px";
                ripple.classList.add("ripple");
                
                this.appendChild(ripple);
                
                setTimeout(() => {
                    ripple.remove();
                }, 600);
            });
        });
    });
';

// รวม footer template
include __DIR__ . '/../includes/footer.php';
?>