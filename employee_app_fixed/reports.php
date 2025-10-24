<?php
/**
 * reports.php
 * หน้ารายงานและสถิติการทำงานแบบละเอียด
 * - สรุปข้อมูลจากทุกสถานที่
 * - แสดงสถิติการทำงานแบบ real-time
 * - กราฟและแผนภูมิแสดงข้อมูล
 */

require_once __DIR__ . '/config.php';

// ตรวจสอบการเข้าสู่ระบบ
if (empty($_SESSION['user'])) {
    header('Location: login.php?error=3'); 
    exit;
}

// กำหนดค่าสำหรับ header template
$page_title = 'รายงานและสถิติการทำงาน';
$current_path = './';
$extra_css = ['assets/reports.css'];

include __DIR__ . '/includes/header.php';

// ดึงข้อมูลจากทุกสถานที่
$conn_checklist = getChecklistConnection();
$locations = ['เมืองสมุทรปราการ', 'พระประแดง', 'พระสมุทรเจดีย์', 'บางพลี', 'บางบ่อ', 'บางเสาธง'];

$total_statistics = [
    'total_locations' => 0,
    'total_products' => 0,
    'total_checked' => 0,
    'total_in_stock' => 0,
    'total_out_of_stock' => 0,
    'total_not_for_sale' => 0,
    'total_pending' => 0,
    'check_rate' => 0,
    'stock_rate' => 0,
    'locations_data' => []
];

$category_statistics = [
    'เครื่องดื่ม' => ['total' => 0, 'checked' => 0, 'in_stock' => 0, 'out_of_stock' => 0, 'not_for_sale' => 0],
    'ขนม' => ['total' => 0, 'checked' => 0, 'in_stock' => 0, 'out_of_stock' => 0, 'not_for_sale' => 0]
];

$recent_activities = [];

if ($conn_checklist) {
    foreach ($locations as $location) {
        $location_data = [
            'name' => $location,
            'total_products' => 0,
            'checked_items' => 0,
            'in_stock' => 0,
            'out_of_stock' => 0,
            'not_for_sale' => 0,
            'pending' => 0,
            'check_rate' => 0,
            'stock_rate' => 0,
            'last_updated' => null,
            'categories' => []
        ];
        
        // ตรวจสอบว่าตารางมีอยู่หรือไม่
        $check_table = "SHOW TABLES LIKE '" . mysqli_real_escape_string($conn_checklist, $location) . "'";
        $table_result = mysqli_query($conn_checklist, $check_table);
        
        if ($table_result && mysqli_num_rows($table_result) > 0) {
            // ดึงข้อมูลทั่วไป
            $stats_sql = "SELECT 
                COUNT(*) as total_products,
                SUM(CASE WHEN status IS NOT NULL AND status != '' THEN 1 ELSE 0 END) as checked_items,
                SUM(CASE WHEN status = 'in_stock' THEN 1 ELSE 0 END) as in_stock,
                SUM(CASE WHEN status = 'out_of_stock' THEN 1 ELSE 0 END) as out_of_stock,
                SUM(CASE WHEN status = 'not_for_sale' THEN 1 ELSE 0 END) as not_for_sale,
                MAX(updated_at) as last_updated
                FROM `" . mysqli_real_escape_string($conn_checklist, $location) . "`";
                
            $stats_result = mysqli_query($conn_checklist, $stats_sql);
            if ($stats_result) {
                $stats = mysqli_fetch_assoc($stats_result);
                $location_data = array_merge($location_data, [
                    'total_products' => (int)$stats['total_products'],
                    'checked_items' => (int)$stats['checked_items'],
                    'in_stock' => (int)$stats['in_stock'],
                    'out_of_stock' => (int)$stats['out_of_stock'],
                    'not_for_sale' => (int)$stats['not_for_sale'],
                    'last_updated' => $stats['last_updated']
                ]);
                
                $location_data['pending'] = $location_data['total_products'] - $location_data['checked_items'];
                $location_data['check_rate'] = $location_data['total_products'] > 0 ? 
                    round(($location_data['checked_items'] / $location_data['total_products']) * 100, 2) : 0;
                $location_data['stock_rate'] = $location_data['checked_items'] > 0 ? 
                    round(($location_data['in_stock'] / $location_data['checked_items']) * 100, 2) : 0;
            }
            
            // ดึงข้อมูลตามหมวดหมู่
            $category_sql = "SELECT 
                COALESCE(category, 'เครื่องดื่ม') as category,
                COUNT(*) as total,
                SUM(CASE WHEN status IS NOT NULL AND status != '' THEN 1 ELSE 0 END) as checked,
                SUM(CASE WHEN status = 'in_stock' THEN 1 ELSE 0 END) as in_stock,
                SUM(CASE WHEN status = 'out_of_stock' THEN 1 ELSE 0 END) as out_of_stock,
                SUM(CASE WHEN status = 'not_for_sale' THEN 1 ELSE 0 END) as not_for_sale
                FROM `" . mysqli_real_escape_string($conn_checklist, $location) . "`
                GROUP BY COALESCE(category, 'เครื่องดื่ม')";
                
            $category_result = mysqli_query($conn_checklist, $category_sql);
            if ($category_result) {
                while ($cat_data = mysqli_fetch_assoc($category_result)) {
                    $category = $cat_data['category'];
                    $location_data['categories'][$category] = [
                        'total' => (int)$cat_data['total'],
                        'checked' => (int)$cat_data['checked'],
                        'in_stock' => (int)$cat_data['in_stock'],
                        'out_of_stock' => (int)$cat_data['out_of_stock'],
                        'not_for_sale' => (int)$cat_data['not_for_sale']
                    ];
                    
                    // เพิ่มข้อมูลในสถิติรวม
                    if (isset($category_statistics[$category])) {
                        $category_statistics[$category]['total'] += (int)$cat_data['total'];
                        $category_statistics[$category]['checked'] += (int)$cat_data['checked'];
                        $category_statistics[$category]['in_stock'] += (int)$cat_data['in_stock'];
                        $category_statistics[$category]['out_of_stock'] += (int)$cat_data['out_of_stock'];
                        $category_statistics[$category]['not_for_sale'] += (int)$cat_data['not_for_sale'];
                    }
                }
            }
            
            // ดึงกิจกรรมล่าสุด
            $activity_sql = "SELECT product_name, status, updated_at 
                FROM `" . mysqli_real_escape_string($conn_checklist, $location) . "`
                WHERE updated_at IS NOT NULL 
                ORDER BY updated_at DESC 
                LIMIT 5";
                
            $activity_result = mysqli_query($conn_checklist, $activity_sql);
            if ($activity_result) {
                while ($activity = mysqli_fetch_assoc($activity_result)) {
                    $recent_activities[] = [
                        'location' => $location,
                        'product_name' => $activity['product_name'],
                        'status' => $activity['status'],
                        'updated_at' => $activity['updated_at']
                    ];
                }
            }
            
            $total_statistics['total_locations']++;
        }
        
        $total_statistics['locations_data'][] = $location_data;
        
        // เพิ่มในสถิติรวม
        $total_statistics['total_products'] += $location_data['total_products'];
        $total_statistics['total_checked'] += $location_data['checked_items'];
        $total_statistics['total_in_stock'] += $location_data['in_stock'];
        $total_statistics['total_out_of_stock'] += $location_data['out_of_stock'];
        $total_statistics['total_not_for_sale'] += $location_data['not_for_sale'];
        $total_statistics['total_pending'] += $location_data['pending'];
    }
    
    // คำนวณเปอร์เซ็นต์รวม
    $total_statistics['check_rate'] = $total_statistics['total_products'] > 0 ? 
        round(($total_statistics['total_checked'] / $total_statistics['total_products']) * 100, 2) : 0;
    $total_statistics['stock_rate'] = $total_statistics['total_checked'] > 0 ? 
        round(($total_statistics['total_in_stock'] / $total_statistics['total_checked']) * 100, 2) : 0;
    
    // เรียงกิจกรรมล่าสุดตามเวลา
    usort($recent_activities, function($a, $b) {
        return strtotime($b['updated_at']) - strtotime($a['updated_at']);
    });
    $recent_activities = array_slice($recent_activities, 0, 10);
}

?>

<div class="container-fluid mt-4">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-lg header-card">
                <div class="card-header bg-gradient-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h1 class="h3 mb-0">
                                <i class="bi bi-graph-up-arrow me-2"></i>รายงานและสถิติการทำงาน
                            </h1>
                            <p class="mb-0 mt-2 opacity-90">ข้อมูลสถิติแบบ Real-time จากทุกสถานที่</p>
                        </div>
                        <div>
                            <a href="index.php" class="btn btn-light btn-sm me-2">
                                <i class="bi bi-house-fill me-1"></i>หน้าหลัก
                            </a>
                            <button class="btn btn-outline-light btn-sm" onclick="refreshData()">
                                <i class="bi bi-arrow-clockwise me-1"></i>รีเฟรช
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Overview Statistics -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-lg">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-pie-chart-fill me-2"></i>สถิติภาพรวม</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                            <div class="stat-card stat-primary">
                                <div class="stat-icon">
                                    <i class="bi bi-building"></i>
                                </div>
                                <div class="stat-info">
                                    <div class="stat-number"><?php echo $total_statistics['total_locations']; ?></div>
                                    <div class="stat-label">สถานที่ทั้งหมด</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                            <div class="stat-card stat-info">
                                <div class="stat-icon">
                                    <i class="bi bi-box-seam"></i>
                                </div>
                                <div class="stat-info">
                                    <div class="stat-number"><?php echo $total_statistics['total_products']; ?></div>
                                    <div class="stat-label">สินค้าทั้งหมด</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                            <div class="stat-card stat-success">
                                <div class="stat-icon">
                                    <i class="bi bi-check-circle-fill"></i>
                                </div>
                                <div class="stat-info">
                                    <div class="stat-number"><?php echo $total_statistics['total_checked']; ?></div>
                                    <div class="stat-label">ตรวจสอบแล้ว</div>
                                    <div class="stat-percentage"><?php echo $total_statistics['check_rate']; ?>%</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                            <div class="stat-card stat-success">
                                <div class="stat-icon">
                                    <i class="bi bi-check2-all"></i>
                                </div>
                                <div class="stat-info">
                                    <div class="stat-number"><?php echo $total_statistics['total_in_stock']; ?></div>
                                    <div class="stat-label">มี Stock</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                            <div class="stat-card stat-danger">
                                <div class="stat-icon">
                                    <i class="bi bi-x-circle-fill"></i>
                                </div>
                                <div class="stat-info">
                                    <div class="stat-number"><?php echo $total_statistics['total_out_of_stock']; ?></div>
                                    <div class="stat-label">สินค้าหมด</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                            <div class="stat-card stat-warning">
                                <div class="stat-icon">
                                    <i class="bi bi-clock-fill"></i>
                                </div>
                                <div class="stat-info">
                                    <div class="stat-number"><?php echo $total_statistics['total_pending']; ?></div>
                                    <div class="stat-label">รอตรวจสอบ</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="row mb-4">
        <div class="col-lg-6 mb-4">
            <div class="card shadow-lg h-100">
                <div class="card-header">
                    <h6 class="mb-0"><i class="bi bi-bar-chart-fill me-2"></i>อัตราการตรวจสอบตามสถานที่</h6>
                </div>
                <div class="card-body">
                    <canvas id="locationChart" width="400" height="300"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-6 mb-4">
            <div class="card shadow-lg h-100">
                <div class="card-header">
                    <h6 class="mb-0"><i class="bi bi-pie-chart-fill me-2"></i>การกระจายสถานะสินค้า</h6>
                </div>
                <div class="card-body">
                    <canvas id="statusChart" width="400" height="300"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Category Statistics -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-lg">
                <div class="card-header">
                    <h6 class="mb-0"><i class="bi bi-grid-3x3-gap-fill me-2"></i>สถิติตามหมวดหมู่สินค้า</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <?php foreach ($category_statistics as $category => $stats): ?>
                        <div class="col-lg-6 mb-3">
                            <div class="category-stat-card">
                                <div class="category-header">
                                    <i class="bi bi-<?php echo $category == 'เครื่องดื่ม' ? 'droplet-fill' : 'emoji-smile-fill'; ?> me-2"></i>
                                    <h6><?php echo $category; ?></h6>
                                </div>
                                <div class="category-stats">
                                    <div class="row text-center">
                                        <div class="col-3">
                                            <div class="mini-stat">
                                                <div class="mini-stat-number"><?php echo $stats['total']; ?></div>
                                                <div class="mini-stat-label">ทั้งหมด</div>
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <div class="mini-stat">
                                                <div class="mini-stat-number text-success"><?php echo $stats['in_stock']; ?></div>
                                                <div class="mini-stat-label">มี Stock</div>
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <div class="mini-stat">
                                                <div class="mini-stat-number text-danger"><?php echo $stats['out_of_stock']; ?></div>
                                                <div class="mini-stat-label">หมด</div>
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <div class="mini-stat">
                                                <div class="mini-stat-number text-secondary"><?php echo $stats['not_for_sale']; ?></div>
                                                <div class="mini-stat-label">ไม่ขาย</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="category-progress">
                                    <?php 
                                    $check_rate = $stats['total'] > 0 ? round(($stats['checked'] / $stats['total']) * 100, 2) : 0;
                                    ?>
                                    <div class="d-flex justify-content-between mb-1">
                                        <span class="small">ตรวจสอบแล้ว</span>
                                        <span class="small"><?php echo $check_rate; ?>%</span>
                                    </div>
                                    <div class="progress" style="height: 6px;">
                                        <div class="progress-bar bg-primary" role="progressbar" 
                                             style="width: <?php echo $check_rate; ?>%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Location Details -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-lg">
                <div class="card-header">
                    <h6 class="mb-0"><i class="bi bi-geo-alt-fill me-2"></i>รายละเอียดแต่ละสถานที่</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>สถานที่</th>
                                    <th>สินค้าทั้งหมด</th>
                                    <th>ตรวจแล้ว</th>
                                    <th>มี Stock</th>
                                    <th>หมด</th>
                                    <th>ไม่ขาย</th>
                                    <th>อัตราการตรวจ</th>
                                    <th>อัตรา Stock</th>
                                    <th>อัปเดตล่าสุด</th>
                                    <th>การดำเนินการ</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($total_statistics['locations_data'] as $location): ?>
                                <tr>
                                    <td>
                                        <strong><?php echo htmlspecialchars($location['name']); ?></strong>
                                    </td>
                                    <td><?php echo $location['total_products']; ?></td>
                                    <td>
                                        <span class="badge bg-primary"><?php echo $location['checked_items']; ?></span>
                                    </td>
                                    <td>
                                        <span class="badge bg-success"><?php echo $location['in_stock']; ?></span>
                                    </td>
                                    <td>
                                        <span class="badge bg-danger"><?php echo $location['out_of_stock']; ?></span>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary"><?php echo $location['not_for_sale']; ?></span>
                                    </td>
                                    <td>
                                        <div class="progress" style="height: 20px; min-width: 80px;">
                                            <div class="progress-bar" role="progressbar" 
                                                 style="width: <?php echo $location['check_rate']; ?>%">
                                                <?php echo $location['check_rate']; ?>%
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="progress" style="height: 20px; min-width: 80px;">
                                            <div class="progress-bar bg-success" role="progressbar" 
                                                 style="width: <?php echo $location['stock_rate']; ?>%">
                                                <?php echo $location['stock_rate']; ?>%
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <?php if ($location['last_updated']): ?>
                                            <small><?php echo date('d/m/Y H:i', strtotime($location['last_updated'])); ?></small>
                                        <?php else: ?>
                                            <small class="text-muted">ไม่มีข้อมูล</small>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <a href="location/checklist/checklist.php?location=<?php echo urlencode($location['name']); ?>" 
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-eye me-1"></i>ดู
                                        </a>
                                        <a href="location/checklist/summary.php?location=<?php echo urlencode($location['name']); ?>" 
                                           class="btn btn-sm btn-outline-success">
                                            <i class="bi bi-file-text me-1"></i>สรุป
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activities -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-lg">
                <div class="card-header">
                    <h6 class="mb-0"><i class="bi bi-clock-history me-2"></i>กิจกรรมล่าสุด</h6>
                </div>
                <div class="card-body">
                    <?php if (!empty($recent_activities)): ?>
                    <div class="activity-timeline">
                        <?php foreach ($recent_activities as $activity): ?>
                        <div class="activity-item">
                            <div class="activity-icon">
                                <i class="bi bi-<?php 
                                    echo $activity['status'] == 'in_stock' ? 'check-circle-fill text-success' : 
                                        ($activity['status'] == 'out_of_stock' ? 'x-circle-fill text-danger' : 
                                        'dash-circle-fill text-secondary'); 
                                ?>"></i>
                            </div>
                            <div class="activity-content">
                                <div class="activity-header">
                                    <strong><?php echo htmlspecialchars($activity['product_name']); ?></strong>
                                    <span class="badge bg-<?php 
                                        echo $activity['status'] == 'in_stock' ? 'success' : 
                                            ($activity['status'] == 'out_of_stock' ? 'danger' : 'secondary'); 
                                    ?>">
                                        <?php 
                                        echo $activity['status'] == 'in_stock' ? 'มี STOCK' : 
                                            ($activity['status'] == 'out_of_stock' ? 'สินค้าหมด' : 'ไม่มีขาย'); 
                                        ?>
                                    </span>
                                </div>
                                <div class="activity-meta">
                                    <small class="text-muted">
                                        <i class="bi bi-geo-alt me-1"></i><?php echo htmlspecialchars($activity['location']); ?>
                                        <i class="bi bi-clock ms-2 me-1"></i><?php echo date('d/m/Y H:i', strtotime($activity['updated_at'])); ?>
                                    </small>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php else: ?>
                    <div class="text-center py-4">
                        <i class="bi bi-inbox display-1 text-muted"></i>
                        <p class="text-muted mt-2">ยังไม่มีกิจกรรมในระบบ</p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
// กำหนด JavaScript สำหรับหน้านี้
$inline_js = '
// ข้อมูลสำหรับกราฟ
const locationData = ' . json_encode($total_statistics['locations_data']) . ';
const totalStats = ' . json_encode($total_statistics) . ';

document.addEventListener("DOMContentLoaded", function() {
    console.log("📊 Reports page loaded");
    initializeCharts();
});

function initializeCharts() {
    // กราฟแท่งแสดงอัตราการตรวจสอบตามสถานที่
    const locationChart = new Chart(document.getElementById("locationChart"), {
        type: "bar",
        data: {
            labels: locationData.map(loc => loc.name),
            datasets: [{
                label: "อัตราการตรวจสอบ (%)",
                data: locationData.map(loc => loc.check_rate),
                backgroundColor: "rgba(54, 162, 235, 0.8)",
                borderColor: "rgba(54, 162, 235, 1)",
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100,
                    ticks: {
                        callback: function(value) {
                            return value + "%";
                        }
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.parsed.y + "%";
                        }
                    }
                }
            }
        }
    });

    // กราฟวงกลมแสดงการกระจายสถานะสินค้า
    const statusChart = new Chart(document.getElementById("statusChart"), {
        type: "doughnut",
        data: {
            labels: ["มี Stock", "สินค้าหมด", "ไม่มีขาย", "รอตรวจสอบ"],
            datasets: [{
                data: [
                    totalStats.total_in_stock,
                    totalStats.total_out_of_stock,
                    totalStats.total_not_for_sale,
                    totalStats.total_pending
                ],
                backgroundColor: [
                    "rgba(40, 167, 69, 0.8)",
                    "rgba(220, 53, 69, 0.8)",
                    "rgba(108, 117, 125, 0.8)",
                    "rgba(255, 193, 7, 0.8)"
                ],
                borderColor: [
                    "rgba(40, 167, 69, 1)",
                    "rgba(220, 53, 69, 1)",
                    "rgba(108, 117, 125, 1)",
                    "rgba(255, 193, 7, 1)"
                ],
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: "bottom"
                }
            }
        }
    });
}

function refreshData() {
    // แสดง loading state
    const refreshBtn = document.querySelector("[onclick=\"refreshData()\"]");
    const originalText = refreshBtn.innerHTML;
    refreshBtn.innerHTML = "<i class=\"bi bi-arrow-clockwise me-1 spin\"></i>กำลังรีเฟรช...";
    refreshBtn.disabled = true;
    
    // รีโหลดหน้า
    setTimeout(() => {
        window.location.reload();
    }, 1000);
}

// CSS animation สำหรับ spin
const style = document.createElement("style");
style.textContent = `
    .spin {
        animation: spin 1s linear infinite;
    }
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
`;
document.head.appendChild(style);
';

include __DIR__ . '/includes/footer.php';
?>