<?php
/**
 * summary.php
 * แสดงสรุปผลการตรวจสอบสินค้า
 * Input (GET):
 * - location (string) - ชื่อสถานที่
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

// เชื่อมต่อฐานข้อมูล checklist
$conn_checklist = getChecklistConnection();
if (!$conn_checklist) {
    die('ไม่สามารถเชื่อมต่อฐานข้อมูลได้');
}

// ดึงสรุปข้อมูล
$summary_sql = "
    SELECT 
        status,
        COUNT(*) as count
    FROM `{$location}` 
    GROUP BY status
";

$summary_result = mysqli_query($conn_checklist, $summary_sql);
$summary = [
    'in_stock' => 0,
    'out_of_stock' => 0,
    'not_for_sale' => 0,
    'null' => 0
];

while ($row = mysqli_fetch_assoc($summary_result)) {
    $status = $row['status'] ?? 'null';
    $summary[$status] = (int)$row['count'];
}

// ดึงรายการทั้งหมด
$items_sql = "SELECT `id`, `product_code`, `product_name`, `status`, `note`, `updated_at` FROM `{$location}` ORDER BY `updated_at` DESC";
$items_result = mysqli_query($conn_checklist, $items_sql);

$items = [];
while ($row = mysqli_fetch_assoc($items_result)) {
    $items[] = $row;
}

// ไม่ต้องปิด connection เองเพราะ Database class จะจัดการให้

// กำหนดค่าสำหรับ header template
$page_title = 'สรุปผลการตรวจสอบ - ' . htmlspecialchars($location);
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
                <div class="card-header bg-info text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h2 class="card-title mb-0">
                                <i class="bi bi-clipboard-data me-2"></i>สรุปผลการตรวจสอบ - <?php echo htmlspecialchars($location); ?>
                            </h2>
                        </div>
                        <div>
                            <a href="checklist.php?location=<?php echo urlencode($location); ?>" class="btn btn-light btn-sm me-2">
                                <i class="bi bi-arrow-left me-1"></i>กลับเช็คลิสต์
                            </a>
                            <a href="../location.php" class="btn btn-light btn-sm">
                                <i class="bi bi-house me-1"></i>หน้าสถานที่
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Summary Cards -->
            <div class="row g-4 mb-4">
                <div class="col-lg-3 col-md-6">
                    <div class="card text-center status-summary-card in-stock">
                        <div class="card-body">
                            <div class="status-icon">
                                <i class="bi bi-check-circle-fill"></i>
                            </div>
                            <h3 class="status-count"><?php echo $summary['in_stock']; ?></h3>
                            <p class="status-label">มี STOCK</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-6">
                    <div class="card text-center status-summary-card out-stock">
                        <div class="card-body">
                            <div class="status-icon">
                                <i class="bi bi-x-circle-fill"></i>
                            </div>
                            <h3 class="status-count"><?php echo $summary['out_of_stock']; ?></h3>
                            <p class="status-label">สินค้าหมด</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-6">
                    <div class="card text-center status-summary-card not-sale">
                        <div class="card-body">
                            <div class="status-icon">
                                <i class="bi bi-dash-circle-fill"></i>
                            </div>
                            <h3 class="status-count"><?php echo $summary['not_for_sale']; ?></h3>
                            <p class="status-label">ไม่มีขาย</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-6">
                    <div class="card text-center status-summary-card pending">
                        <div class="card-body">
                            <div class="status-icon">
                                <i class="bi bi-clock-fill"></i>
                            </div>
                            <h3 class="status-count"><?php echo $summary['null']; ?></h3>
                            <p class="status-label">รอตรวจสอบ</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Items List -->
            <div class="card shadow-lg">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="bi bi-list-ul me-2"></i>รายการสินค้าทั้งหมด (<?php echo count($items); ?> รายการ)
                    </h5>
                </div>
                <div class="card-body">
                    <?php if (empty($items)): ?>
                        <div class="alert alert-warning text-center">
                            <i class="bi bi-exclamation-triangle me-2"></i>ไม่มีรายการสินค้าในระบบ
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>#</th>
                                        <th>รหัสสินค้า</th>
                                        <th>ชื่อสินค้า</th>
                                        <th>สถานะ</th>
                                        <th>หมายเหตุ</th>
                                        <th>วันที่อัปเดต</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($items as $index => $item): 
                                        $status_badge = '';
                                        $status_text = '';
                                        
                                        switch ($item['status']) {
                                            case 'in_stock':
                                                $status_badge = 'bg-success';
                                                $status_text = 'มี STOCK';
                                                break;
                                            case 'out_of_stock':
                                                $status_badge = 'bg-danger';
                                                $status_text = 'สินค้าหมด';
                                                break;
                                            case 'not_for_sale':
                                                $status_badge = 'bg-secondary';
                                                $status_text = 'ไม่มีขาย';
                                                break;
                                            default:
                                                $status_badge = 'bg-warning';
                                                $status_text = 'รอตรวจสอบ';
                                                break;
                                        }
                                    ?>
                                        <tr>
                                            <td><?php echo $index + 1; ?></td>
                                            <td><?php echo htmlspecialchars($item['product_code']); ?></td>
                                            <td><?php echo htmlspecialchars($item['product_name']); ?></td>
                                            <td>
                                                <span class="badge <?php echo $status_badge; ?>"><?php echo $status_text; ?></span>
                                            </td>
                                            <td><?php echo htmlspecialchars($item['note'] ?? '-'); ?></td>
                                            <td><?php echo $item['updated_at'] ? date('d/m/Y H:i', strtotime($item['updated_at'])) : '-'; ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="text-center mt-4">
                <a href="checklist.php?location=<?php echo urlencode($location); ?>" class="btn btn-primary btn-lg me-2">
                    <i class="bi bi-arrow-left me-2"></i>กลับไปแก้ไข
                </a>
                <button class="btn btn-success btn-lg me-2" onclick="window.print()">
                    <i class="bi bi-printer me-2"></i>พิมพ์รายงาน
                </button>
                <a href="../location.php" class="btn btn-secondary btn-lg">
                    <i class="bi bi-house me-2"></i>เลือกสถานที่อื่น
                </a>
            </div>
        </div>
    </div>
</div>

<style>
.status-summary-card {
    border: none;
    border-radius: 15px;
    transition: all 0.3s ease;
}

.status-summary-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.1);
}

.status-summary-card.in-stock {
    background: linear-gradient(135deg, #10b981, #059669);
    color: white;
}

.status-summary-card.out-stock {
    background: linear-gradient(135deg, #ef4444, #dc2626);
    color: white;
}

.status-summary-card.not-sale {
    background: linear-gradient(135deg, #6b7280, #4b5563);
    color: white;
}

.status-summary-card.pending {
    background: linear-gradient(135deg, #f59e0b, #d97706);
    color: white;
}

.status-icon {
    font-size: 3rem;
    margin-bottom: 1rem;
}

.status-count {
    font-size: 2.5rem;
    font-weight: bold;
    margin-bottom: 0.5rem;
}

.status-label {
    font-size: 1.1rem;
    margin: 0;
    opacity: 0.9;
}

@media print {
    .btn, .card-header .btn {
        display: none !important;
    }
    
    .status-summary-card {
        border: 1px solid #ddd !important;
        color: #333 !important;
        background: white !important;
    }
}
</style>

<?php
// รวม footer template
include __DIR__ . '/../../includes/footer.php';
?>