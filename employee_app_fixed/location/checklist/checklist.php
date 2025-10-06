<?php
require_once __DIR__ . '/../../config.php';
if (empty($_SESSION['user'])) {
    header('Location: ../../login.php?error=3'); exit;
}

$location = isset($_GET['location']) ? $_GET['location'] : '';
$locations = ['เมืองสมุทรปราการ', 'พระประแดง', 'พระสมุทรเจดีย์', 'บางพลี', 'บางบ่อ', 'บางเสาธง'];

if (!in_array($location, $locations, true)) { 
    header('Location: ../location.php'); exit; 
}

// เชื่อมต่อฐานข้อมูล checklist
$conn_checklist = getChecklistConnection();
if (!$conn_checklist) {
    die('ไม่สามารถเชื่อมต่อฐานข้อมูลได้');
}

$table = $location;
$sql = "SELECT `id`,`product_code`,`product_name`,`image_path`,`status`,`note` FROM `{$table}` ORDER BY `id` ASC";
$res = mysqli_query($conn_checklist, $sql);
if (!$res) { 
    die('Query error: '.mysqli_error($conn_checklist)); 
}

$rows = [];
while ($row = mysqli_fetch_assoc($res)) { 
    $rows[] = $row; 
}
mysqli_close($conn_checklist);

// กำหนดค่าสำหรับ header template
$page_title = 'เช็คลิสต์สินค้า - ' . htmlspecialchars($location);
$current_path = '../../';
$extra_css = ['../assets/location.css', 'checklist.css'];

// รวม header template
include __DIR__ . '/../../includes/header.php';
?>
  
  <div class="container mt-4">
    <div class="row justify-content-center">
      <div class="col-md-12">
        
        <!-- Header Card -->
        <div class="card shadow-lg mb-4">
          <div class="card-header bg-primary text-white">
            <div class="d-flex justify-content-between align-items-center">
              <div>
                <h2 class="card-title mb-0">
                  <i class="bi bi-check2-square me-2"></i>เช็คลิสต์สินค้า - <?php echo htmlspecialchars($location); ?>
                </h2>
              </div>
              <a href="../location.php" class="btn btn-light btn-sm">
                <i class="bi bi-arrow-left me-1"></i>กลับ
              </a>
            </div>
          </div>
        </div>

        <!-- Messages -->
        <?php if (isset($_SESSION['message'])): ?>
          <div class="alert alert-success alert-custom alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>
            <?php echo htmlspecialchars($_SESSION['message']); unset($_SESSION['message']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
          </div>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['error'])): ?>
          <div class="alert alert-danger alert-custom alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-circle-fill me-2"></i>
            <?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
          </div>
        <?php endif; ?>

        <!-- Main Form Card -->
        <div class="card shadow-lg mb-4">
          <div class="card-header bg-light">
            <h5 class="mb-0"><i class="bi bi-list-check me-2"></i>รายการสินค้า (<?php echo count($rows); ?> รายการ)</h5>
          </div>
          <div class="card-body">
            <form method="post" action="save.php">
              <input type="hidden" name="location" value="<?php echo htmlspecialchars($location); ?>">
              
              <?php foreach ($rows as $r): 
                $statusClass = '';
                switch ($r['status']) {
                  case 'in_stock': $statusClass = 'status-in_stock'; break;
                  case 'out_of_stock': $statusClass = 'status-out_of_stock'; break;
                  case 'not_for_sale': $statusClass = 'status-not_for_sale'; break;
                  default: $statusClass = 'status-none'; break;
                }
              ?>
                <div class="card mb-3 status-card <?php echo $statusClass; ?>">
                  <div class="card-body">
                    <div class="row align-items-center">
                      <div class="col-md-2 text-center">
                        <?php if (!empty($r['image_path'])): ?>
                          <img src="<?php echo htmlspecialchars($r['image_path']); ?>" alt="Product Image" class="product-image">
                        <?php else: ?>
                          <div class="no-image">
                            <i class="bi bi-image" style="font-size: 24px;"></i>
                          </div>
                        <?php endif; ?>
                      </div>
                      <div class="col-md-3">
                        <h6 class="mb-1 text-primary"><?php echo htmlspecialchars($r['product_name']); ?></h6>
                        <small class="text-muted">รหัส: <?php echo htmlspecialchars($r['product_code']); ?></small>
                      </div>
                      <div class="col-md-4">
                        <div class="row g-2">
                          <?php 
                          $options = [
                            'in_stock' => ['label' => 'มี STOCK', 'color' => 'success', 'icon' => 'check-circle'],
                            'out_of_stock' => ['label' => 'สินค้าหมด', 'color' => 'danger', 'icon' => 'x-circle'],
                            'not_for_sale' => ['label' => 'ไม่มีขาย', 'color' => 'secondary', 'icon' => 'dash-circle']
                          ];
                          ?>
                          <?php foreach ($options as $val => $opt): ?>
                            <div class="col-12">
                              <div class="form-check">
                                <input class="form-check-input" type="radio" 
                                       name="status[<?php echo (int)$r['id']; ?>]" 
                                       value="<?php echo $val; ?>"
                                       id="status_<?php echo $r['id']; ?>_<?php echo $val; ?>"
                                       <?php echo ($r['status'] === $val) ? 'checked' : ''; ?>>
                                <label class="form-check-label text-<?php echo $opt['color']; ?>" 
                                       for="status_<?php echo $r['id']; ?>_<?php echo $val; ?>">
                                  <i class="bi bi-<?php echo $opt['icon']; ?> me-1"></i>
                                  <?php echo $opt['label']; ?>
                                </label>
                              </div>
                            </div>
                          <?php endforeach; ?>
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="mb-2">
                          <label class="form-label small">หมายเหตุ</label>
                          <input type="text" class="form-control form-control-sm" 
                                 name="note[<?php echo (int)$r['id']; ?>]" 
                                 placeholder="ยืนยันใส่เลข 1" 
                                 value="<?php echo htmlspecialchars($r['note'] ?? ''); ?>">
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              <?php endforeach; ?>
              
              <div class="d-flex justify-content-between align-items-center mt-4">
                <div>
                  <button type="button" class="btn btn-info btn-lg me-2" onclick="clearAllData('reset')" title="รีเซ็ตข้อมูลการตรวจสอบ (เก็บรายการสินค้าไว้)">
                    <i class="bi bi-arrow-clockwise me-2"></i>รีเซ็ตข้อมูล
                  </button>
                  <button type="button" class="btn btn-danger btn-lg" onclick="clearAllData('delete_all')" title="ลบข้อมูลทั้งหมดออกจากฐานข้อมูล">
                    <i class="bi bi-trash3-fill me-2"></i>ลบข้อมูลทั้งหมด
                  </button>
                </div>
                <div>
                  <button type="button" class="btn btn-warning btn-lg me-2" onclick="alert('ฟีเจอร์ถ่ายรูปยังไม่พร้อมใช้งาน');">
                    <i class="bi bi-camera-fill me-2"></i>เพิ่มรูปถ่ายสินค้า
                  </button>
                  <button type="submit" class="btn btn-success btn-lg me-2">
                    <i class="bi bi-check2-all me-2"></i>บันทึกสถานะทั้งหมด
                  </button>
                  <a href="../location.php" class="btn btn-secondary btn-lg">
                    <i class="bi bi-x-lg me-2"></i>ยกเลิก
                  </a>
                </div>
              </div>
            </form>
          </div>
        </div>
    </div>
  </div>

<?php
// กำหนด JavaScript สำหรับหน้านี้
$inline_js = '
    function clearAllData(action) {
      let confirmMessage = "";
      if (action === "delete_all") {
        confirmMessage = "คุณแน่ใจหรือไม่ที่จะลบข้อมูลทั้งหมดออกจากฐานข้อมูล?\\nการกระทำนี้จะลบรายการสินค้าทั้งหมดและไม่สามารถกู้คืนได้!";
      } else {
        confirmMessage = "คุณแน่ใจหรือไม่ที่จะรีเซ็ตข้อมูลการตรวจสอบ?\\nการกระทำนี้จะลบเฉพาะสถานะและหมายเหตุ แต่เก็บรายการสินค้าไว้";
      }
      
      if (confirm(confirmMessage)) {
        // สร้าง form สำหรับส่งข้อมูลไปยัง clear_data.php
        const form = document.createElement("form");
        form.method = "POST";
        form.action = "clear_data.php";
        
        // เพิ่ม input สำหรับ location
        const locationInput = document.createElement("input");
        locationInput.type = "hidden";
        locationInput.name = "location";
        locationInput.value = "' . htmlspecialchars($location) . '";
        form.appendChild(locationInput);
        
        // เพิ่ม input สำหรับ action
        const actionInput = document.createElement("input");
        actionInput.type = "hidden";
        actionInput.name = "action";
        actionInput.value = action;
        form.appendChild(actionInput);
        
        // เพิ่ม form เข้าไปใน body และ submit
        document.body.appendChild(form);
        form.submit();
      }
    }
    
    function showMessage(message, type) {
      // ลบ alert เก่าออกก่อน
      const existingAlert = document.querySelector(".alert");
      if (existingAlert) {
        existingAlert.remove();
      }
      
      // สร้าง alert ใหม่
      const alertDiv = document.createElement("div");
      alertDiv.className = `alert alert-${type} alert-custom alert-dismissible fade show`;
      alertDiv.innerHTML = `
        <i class="bi bi-${type === "success" ? "check-circle-fill" : "exclamation-circle-fill"} me-2"></i>
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      `;
      
      // แทรกข้อความก่อน main form card
      const mainCard = document.querySelector(".card.shadow-lg.mb-4:last-of-type");
      mainCard.parentNode.insertBefore(alertDiv, mainCard);
      
      // ลบข้อความหลังจาก 5 วินาที
      setTimeout(() => {
        if (alertDiv) {
          alertDiv.remove();
        }
      }, 5000);
    }
    
    // เพิ่ม event listener สำหรับการเปลี่ยนสี status card เมื่อเลือก radio button
    document.addEventListener("DOMContentLoaded", function() {
      const radioButtons = document.querySelectorAll("input[type=\"radio\"]");
      radioButtons.forEach(radio => {
        radio.addEventListener("change", function() {
          if (this.checked) {
            const card = this.closest(".status-card");
            // ลบ class สถานะเก่าทั้งหมด
            card.classList.remove("status-in_stock", "status-out_of_stock", "status-not_for_sale", "status-none");
            // เพิ่ม class สถานะใหม่
            card.classList.add("status-" + this.value);
          }
        });
      });
    });
';

// รวม footer template
include __DIR__ . '/../../includes/footer.php';
?>