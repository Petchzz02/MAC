<?php
/**
 * checklist.php
 * หน้าเช็คลิสต์สินค้าสำหรับแต่ละสถานที่
 * Input (GET): location - ชื่อสถานที่
 */
require_once __DIR__ . '/../../config.php';

if (empty($_SESSION['user'])) {
    header('Location: ../../login.php?error=3'); 
    exit;
}

$location = isset($_GET['location']) ? $_GET['location'] : '';
$locations = ['เมืองสมุทรปราการ', 'พระประแดง', 'พระสมุทรเจดีย์', 'บางพลี', 'บางบ่อ', 'บางเสาธง'];

if (!in_array($location, $locations, true)) { 
    header('Location: ../location.php'); 
    exit; 
}

$conn_checklist = getChecklistConnection();
if (!$conn_checklist) {
    $_SESSION['error'] = 'ไม่สามารถเชื่อมต่อฐานข้อมูลได้: โปรดตรวจสอบการตั้งค่า';
    header('Location: ../location.php');
    exit;
}

// เพิ่มการตั้งค่าเพิ่มเติมสำหรับการจัดการ charset
mysqli_query($conn_checklist, "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci");
mysqli_query($conn_checklist, "SET character_set_connection=utf8mb4");
mysqli_query($conn_checklist, "SET character_set_client=utf8mb4");
mysqli_query($conn_checklist, "SET character_set_results=utf8mb4");

// ตรวจสอบและป้องกันชื่อตาราง - ใช้ mapping สำหรับความปลอดภัย
$table = $location;
$valid_tables = ['เมืองสมุทรปราการ', 'พระประแดง', 'พระสมุทรเจดีย์', 'บางพลี', 'บางบ่อ', 'บางเสาธง'];

// Mapping table names เพื่อป้องกันปัญหา charset
$table_mapping = [
    'เมืองสมุทรปราการ' => 'เมืองสมุทรปราการ',
    'พระประแดง' => 'พระประแดง', 
    'พระสมุทรเจดีย์' => 'พระสมุทรเจดีย์',
    'บางพลี' => 'บางพลี',
    'บางบ่อ' => 'บางบ่อ',
    'บางเสาธง' => 'บางเสาธง'
];

if (!in_array($table, $valid_tables, true) || !isset($table_mapping[$table])) {
    $_SESSION['error'] = 'ตารางไม่ถูกต้อง';
    mysqli_close($conn_checklist);
    header('Location: ../location.php');
    exit;
}

// ใช้ชื่อตารางจาก mapping
$safe_table_name = $table_mapping[$table];

// ตรวจสอบว่าตารางมีอยู่จริงในฐานข้อมูล โดยใช้วิธีที่ปลอดภัย
$tables_query = "SHOW TABLES";
$tables_result = mysqli_query($conn_checklist, $tables_query);
$table_exists = false;

if ($tables_result) {
    while ($row = mysqli_fetch_row($tables_result)) {
        if ($row[0] === $safe_table_name) {
            $table_exists = true;
            break;
        }
    }
}

if (!$table_exists) {
    $_SESSION['error'] = 'ไม่พบตาราง: ' . htmlspecialchars($table);
    mysqli_close($conn_checklist);
    header('Location: ../location.php');
    exit;
}

// เตรียม SQL statement อย่างปลอดภัย
$sql = sprintf(
    "SELECT `id`, `product_code`, `product_name`, `image_path`, `status`, `note` FROM `%s` ORDER BY `id` ASC",
    mysqli_real_escape_string($conn_checklist, $safe_table_name)
);

// เพิ่มการ debug แบบละเอียด
error_log("=== Checklist Debug Info ===");
error_log("Location: " . $location);
error_log("Safe table name: " . $safe_table_name);
error_log("SQL Query: " . $sql);
error_log("Character set: " . mysqli_character_set_name($conn_checklist));

$res = mysqli_query($conn_checklist, $sql);
if (!$res) { 
    $error_msg = 'เกิดข้อผิดพลาดในการดึงข้อมูล: ' . mysqli_error($conn_checklist);
    $errno = mysqli_errno($conn_checklist);
    error_log("Database Error Number: " . $errno);
    error_log("Database Error Message: " . $error_msg);
    error_log("SQL State: " . mysqli_sqlstate($conn_checklist));
    $_SESSION['error'] = $error_msg . " (Error Code: $errno)";
    mysqli_close($conn_checklist);
    header('Location: ../location.php');
    exit;
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

include __DIR__ . '/../../includes/header.php';
?>
  
  <div class="container mt-4">
    <div class="row justify-content-center">
      <div class="col-md-12">
        
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

        <div class="card shadow-lg mb-4">
          <div class="card-header bg-light">
            <h5 class="mb-0"><i class="bi bi-list-check me-2"></i>รายการสินค้า (<?php echo count($rows); ?> รายการ)</h5>
          </div>
          <div class="card-body">
            <form method="post" action="save.php" id="checklistForm">
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
                          <label class="form-label small">หมายเหตุ <span class="text-danger">*</span></label>
                          <input type="text" class="form-control form-control-sm note-required" 
                                 name="note[<?php echo (int)$r['id']; ?>]" 
                                 placeholder="ยืนยันใส่เลข 1" 
                                 value="<?php echo htmlspecialchars($r['note'] ?? ''); ?>"
                                 required>
                          <div class="invalid-feedback">
                            กรุณาใส่หมายเหตุ
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              <?php endforeach; ?>
              
              <div class="d-flex justify-content-between align-items-center mt-4">
                <div>
                  <a href="summary.php?location=<?php echo urlencode($location); ?>" class="btn btn-warning btn-lg me-2">
                    <i class="bi bi-clipboard-data me-2"></i>ดูสรุปผล
                  </a>
                </div>
                <div>
                  <button type="submit" class="btn btn-success btn-lg me-2" id="saveBtn">
                    <i class="bi bi-check2-all me-2"></i>บันทึก
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
include __DIR__ . '/../../includes/footer.php';
?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('🚀 Checklist page loaded');
    
    // Form validation and submission
    const form = document.getElementById('checklistForm');
    const saveBtn = document.getElementById('saveBtn');
    
    if (form && saveBtn) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            let isValid = true;
            const noteInputs = document.querySelectorAll('.note-required');
            
            // Reset validation states
            noteInputs.forEach(input => {
                input.classList.remove('is-invalid', 'is-valid');
            });
            
            // Check each note input
            noteInputs.forEach(input => {
                if (!input.value.trim()) {
                    input.classList.add('is-invalid');
                    isValid = false;
                } else {
                    input.classList.add('is-valid');
                }
            });
            
            if (!isValid) {
                alert('กรุณาใส่หมายเหตุให้ครบทุกรายการ');
                // Scroll to first invalid input
                const firstInvalid = document.querySelector('.note-required.is-invalid');
                if (firstInvalid) {
                    firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    firstInvalid.focus();
                }
                return;
            }
            
            // Show loading state
            const originalText = saveBtn.innerHTML;
            saveBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>กำลังบันทึก...';
            saveBtn.disabled = true;
            
            // Submit form
            this.submit();
        });
    }
    
    // Real-time note validation
    document.querySelectorAll('.note-required').forEach(input => {
        input.addEventListener('input', function() {
            if (this.value.trim()) {
                this.classList.remove('is-invalid');
                this.classList.add('is-valid');
            } else {
                this.classList.remove('is-valid');
            }
        });
    });
    
    console.log('✅ Event listeners attached successfully');
});
</script>