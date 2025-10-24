<?php
/**
 * location.php
 * หน้าเลือกสถานที่ทำงาน
 * - แสดงรายการสถานที่ที่มีอยู่ในระบบ
 * - ลิงก์ไปยังหน้า checklist ของแต่ละสถานที่
 * - แสดงสถิติข้อมูลจากฐานข้อมูลจริง
 */

// รวม config เพื่อเชื่อมต่อฐานข้อมูล
require_once __DIR__ . '/../config.php';

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

// ดึงสถิติจากฐานข้อมูล checklist
$conn_checklist = getChecklistConnection();
$total_items = 0;
$total_checked = 0;
$total_pending = 0;

if ($conn_checklist) {
    foreach ($locations as &$location) {
        $table_name = mysqli_real_escape_string($conn_checklist, $location['name']);
        
        // ตรวจสอบว่าตารางมีอยู่หรือไม่
        $check_table = "SHOW TABLES LIKE '$table_name'";
        $table_result = mysqli_query($conn_checklist, $check_table);
        
        if ($table_result && mysqli_num_rows($table_result) > 0) {
            // นับจำนวนรายการทั้งหมด
            $count_sql = "SELECT COUNT(*) as total, 
                          SUM(CASE WHEN status IS NOT NULL AND status != '' THEN 1 ELSE 0 END) as checked
                          FROM `$table_name`";
            $count_result = mysqli_query($conn_checklist, $count_sql);
            
            if ($count_result) {
                $count_data = mysqli_fetch_assoc($count_result);
                $location['total_items'] = (int)$count_data['total'];
                $location['checked_items'] = (int)$count_data['checked'];
                $location['pending_items'] = $location['total_items'] - $location['checked_items'];
                
                $total_items += $location['total_items'];
                $total_checked += $location['checked_items'];
                $total_pending += $location['pending_items'];
            } else {
                $location['total_items'] = 0;
                $location['checked_items'] = 0;
                $location['pending_items'] = 0;
            }
        } else {
            $location['total_items'] = 0;
            $location['checked_items'] = 0;
            $location['pending_items'] = 0;
        }
    }
    // ไม่ต้องปิด connection เองเพราะ Database class จะจัดการให้
} else {
    // ถ้าเชื่อมต่อไม่ได้ ใช้ค่าเริ่มต้น
    foreach ($locations as &$location) {
        $location['total_items'] = 8;
        $location['checked_items'] = 0;
        $location['pending_items'] = 8;
    }
    $total_items = count($locations) * 8;
    $total_checked = 0;
    $total_pending = $total_items;
}
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
                                        <span class="stat-number"><?php echo $location['total_items']; ?></span>
                                        <span class="stat-label">รายการสินค้า</span>
                                    </div>
                                    <div class="stat-item">
                                        <span class="stat-number"><?php echo $location['checked_items']; ?></span>
                                        <span class="stat-label">ตรวจแล้ว</span>
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
                                        <div class="stat-number"><?php echo $total_items; ?></div>
                                        <div class="stat-label">รายการสินค้ารวม</div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="stat-box">
                                        <div class="stat-icon">
                                            <i class="bi bi-check-circle-fill"></i>
                                        </div>
                                        <div class="stat-number"><?php echo $total_checked; ?></div>
                                        <div class="stat-label">ตรวจสอบแล้ว</div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="stat-box">
                                        <div class="stat-icon">
                                            <i class="bi bi-clock-fill"></i>
                                        </div>
                                        <div class="stat-number"><?php echo $total_pending; ?></div>
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
        console.log("🚀 Location page loaded");
        
        // เอฟเฟ็กต์ hover สำหรับ location cards
        const locationCards = document.querySelectorAll(".location-card");
        locationCards.forEach((card, index) => {
            // เพิ่ม animation delay
            card.style.animationDelay = (index * 0.1) + "s";
            
            card.addEventListener("mouseenter", function() {
                this.style.transform = "translateY(-8px) scale(1.02)";
                this.style.boxShadow = "0 20px 40px rgba(0,0,0,0.15)";
                this.style.zIndex = "10";
            });
            
            card.addEventListener("mouseleave", function() {
                this.style.transform = "translateY(0) scale(1)";
                this.style.boxShadow = "0 4px 15px rgba(0,0,0,0.08)";
                this.style.zIndex = "1";
            });
        });
        
        // เอฟเฟ็กต์ click ripple สำหรับปุ่ม
        const buttons = document.querySelectorAll(".location-btn");
        buttons.forEach(button => {
            button.addEventListener("click", function(e) {
                // สร้าง ripple effect
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
                
                // แสดง loading state
                const originalText = this.innerHTML;
                this.innerHTML = "<i class=\"bi bi-arrow-clockwise me-2\" style=\"animation: spin 1s linear infinite;\"></i>กำลังเข้าสู่ระบบ...";
                this.disabled = true;
                
                // เอา ripple ออกหลัง animation เสร็จ
                setTimeout(() => {
                    if (ripple.parentNode) {
                        ripple.remove();
                    }
                }, 600);
                
                // รีเซ็ตปุ่มหากไม่มีการ redirect (สำหรับ fallback)
                setTimeout(() => {
                    this.innerHTML = originalText;
                    this.disabled = false;
                }, 3000);
            });
        });
        
        // เอฟเฟ็กต์ animated counter สำหรับตัวเลขสถิติ
        const animateCounters = () => {
            const counters = document.querySelectorAll(".stat-number");
            counters.forEach(counter => {
                const target = parseInt(counter.textContent);
                let current = 0;
                const increment = target / 30; // 30 frames animation
                
                const updateCounter = () => {
                    if (current < target) {
                        current += increment;
                        counter.textContent = Math.ceil(current);
                        requestAnimationFrame(updateCounter);
                    } else {
                        counter.textContent = target;
                    }
                };
                
                // เริ่ม animation หลังจาก delay เล็กน้อย
                setTimeout(updateCounter, Math.random() * 500);
            });
        };
        
        // เริ่ม counter animation
        setTimeout(animateCounters, 500);
        
        // เพิ่มการทำงานของ stats boxes
        const statBoxes = document.querySelectorAll(".stat-box");
        statBoxes.forEach((box, index) => {
            box.addEventListener("mouseenter", function() {
                this.style.transform = "translateY(-3px) scale(1.05)";
                this.style.boxShadow = "0 8px 25px rgba(0,0,0,0.1)";
            });
            
            box.addEventListener("mouseleave", function() {
                this.style.transform = "translateY(0) scale(1)";
                this.style.boxShadow = "0 2px 10px rgba(0,0,0,0.05)";
            });
        });
        
        // เพิ่ม scroll reveal effect
        const observerOptions = {
            threshold: 0.1,
            rootMargin: "0px 0px -50px 0px"
        };
        
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = "1";
                    entry.target.style.transform = "translateY(0)";
                }
            });
        }, observerOptions);
        
        // สังเกตการณ์ elements ที่ต้องการ animate
        document.querySelectorAll(".stats-card").forEach(el => {
            el.style.opacity = "0";
            el.style.transform = "translateY(30px)";
            el.style.transition = "all 0.6s ease";
            observer.observe(el);
        });
        
        console.log("✅ All event listeners and animations initialized");
    });
    
    // เพิ่ม CSS animation สำหรับ spin
    const style = document.createElement("style");
    style.textContent = `
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        .main-content {
            min-height: calc(100vh - 200px);
        }
        
        .location-card {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            z-index: 1;
        }
        
        .stat-box {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
    `;
    document.head.appendChild(style);
';

// รวม footer template
include __DIR__ . '/../includes/footer.php';
?>