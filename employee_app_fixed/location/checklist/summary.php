<?php
require_once __DIR__ . '/../../config.php';
if (empty($_SESSION['user'])) {
    header('Location: ../../login.php?error=3'); exit;
}

$conn_checklist = getChecklistConnection();
if (!$conn_checklist) {
    die('ไม่สามารถเชื่อมต่อฐานข้อมูลได้');
}

$locations = ['เมืองสมุทรปราการ', 'พระประแดง', 'พระสมุทรเจดีย์', 'บางพลี', 'บางบ่อ', 'บางเสาธง'];
$summary_data = [];

foreach ($locations as $location) {
    $table = $location;
    $sql = "SELECT 
                COUNT(*) as total,
                SUM(CASE WHEN status = 'in_stock' THEN 1 ELSE 0 END) as in_stock,
                SUM(CASE WHEN status = 'out_of_stock' THEN 1 ELSE 0 END) as out_of_stock,
                SUM(CASE WHEN status = 'not_for_sale' THEN 1 ELSE 0 END) as not_for_sale,
                SUM(CASE WHEN status IS NULL THEN 1 ELSE 0 END) as pending
            FROM `{$table}`";
    
    $result = mysqli_query($conn_checklist, $sql);
    if ($result) {
        $data = mysqli_fetch_assoc($result);
        $summary_data[$location] = $data;
    }
}
mysqli_close($conn_checklist);
?>
<!doctype html>
<html lang="th">
<head>
  <meta charset="utf-8">
  <title>สรุปผลการตรวจสอบสินค้า</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
  <link rel="stylesheet" href="../assets/location.css">
  <style>
    .summary-card {
      transition: all 0.3s ease;
    }
    .summary-card:hover {
      transform: translateY(-3px);
      box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    }
    .progress-custom {
      height: 8px;
      border-radius: 10px;
    }
  </style>
</head>
<body>
  <?php 
  $nav_content = file_get_contents(__DIR__ . '/../../nav.php');
  $nav_content = str_replace('href="index.php"', 'href="../../index.php"', $nav_content);
  $nav_content = str_replace('href="/../location/location.php"', 'href="../location.php"', $nav_content);
  $nav_content = str_replace('href="/../history/history.php"', 'href="../../history/history.php"', $nav_content);
  $nav_content = str_replace('href="logout.php"', 'href="../../logout.php"', $nav_content);
  $nav_content = str_replace('href="login.php"', 'href="../../login.php"', $nav_content);
  $nav_content = str_replace('href="assets/nav.css"', 'href="../../assets/nav.css"', $nav_content);
  echo $nav_content;
  ?>
  
  <div class="container mt-4">
    <div class="row justify-content-center">
      <div class="col-md-12">
        
        <!-- Header -->
        <div class="card shadow-lg mb-4">
          <div class="card-header bg-info text-white">
            <div class="d-flex justify-content-between align-items-center">
              <h2 class="card-title mb-0">
                <i class="bi bi-graph-up me-2"></i>สรุปผลการตรวจสอบสินค้า
              </h2>
              <div>
                <a href="../location.php" class="btn btn-light btn-sm me-2">
                  <i class="bi bi-arrow-left me-1"></i>กลับ
                </a>
                <button onclick="window.print()" class="btn btn-outline-light btn-sm">
                  <i class="bi bi-printer me-1"></i>พิมพ์
                </button>
              </div>
            </div>
          </div>
        </div>

        <!-- Summary Cards -->
        <div class="row">
          <?php 
          $colors = ['primary', 'success', 'info', 'warning', 'secondary', 'danger'];
          $icons = ['building', 'geo-alt', 'bookmark', 'house', 'water', 'flag'];
          $i = 0;
          
          foreach ($summary_data as $location => $data): 
            $total = $data['total'];
            $checked = $total - $data['pending'];
            $progress = $total > 0 ? ($checked / $total) * 100 : 0;
          ?>
            <div class="col-md-6 col-lg-4 mb-4">
              <div class="card summary-card border-<?php echo $colors[$i]; ?> h-100">
                <div class="card-header bg-<?php echo $colors[$i]; ?> text-white">
                  <h5 class="card-title mb-0">
                    <i class="bi bi-<?php echo $icons[$i]; ?> me-2"></i>
                    <?php echo htmlspecialchars($location); ?>
                  </h5>
                </div>
                <div class="card-body">
                  <div class="row text-center mb-3">
                    <div class="col-6">
                      <div class="h4 text-primary mb-0"><?php echo $total; ?></div>
                      <small class="text-muted">สินค้าทั้งหมด</small>
                    </div>
                    <div class="col-6">
                      <div class="h4 text-success mb-0"><?php echo $checked; ?></div>
                      <small class="text-muted">ตรวจสอบแล้ว</small>
                    </div>
                  </div>
                  
                  <div class="progress progress-custom mb-3">
                    <div class="progress-bar bg-success" style="width: <?php echo $progress; ?>%"></div>
                  </div>
                  <div class="text-center mb-3">
                    <small class="text-muted">ความคืบหน้า <?php echo number_format($progress, 1); ?>%</small>
                  </div>
                  
                  <div class="row g-2">
                    <div class="col-4 text-center">
                      <div class="badge bg-success fs-6 w-100"><?php echo $data['in_stock']; ?></div>
                      <small class="text-success d-block mt-1">มี STOCK</small>
                    </div>
                    <div class="col-4 text-center">
                      <div class="badge bg-danger fs-6 w-100"><?php echo $data['out_of_stock']; ?></div>
                      <small class="text-danger d-block mt-1">สินค้าหมด</small>
                    </div>
                    <div class="col-4 text-center">
                      <div class="badge bg-secondary fs-6 w-100"><?php echo $data['not_for_sale']; ?></div>
                      <small class="text-secondary d-block mt-1">ไม่มีขาย</small>
                    </div>
                  </div>
                  
                  <?php if ($data['pending'] > 0): ?>
                    <div class="mt-3 text-center">
                      <div class="badge bg-warning text-dark fs-6"><?php echo $data['pending']; ?> รายการรอตรวจสอบ</div>
                    </div>
                  <?php endif; ?>
                </div>
                <div class="card-footer bg-light">
                  <a href="checklist.php?location=<?php echo urlencode($location); ?>" 
                     class="btn btn-<?php echo $colors[$i]; ?> btn-sm w-100">
                    <i class="bi bi-check-square me-1"></i>ไปที่เช็คลิสต์
                  </a>
                </div>
              </div>
            </div>
          <?php 
            $i++;
          endforeach; 
          ?>
        </div>

        <!-- Overall Statistics -->
        <?php
        $total_all = array_sum(array_column($summary_data, 'total'));
        $in_stock_all = array_sum(array_column($summary_data, 'in_stock'));
        $out_of_stock_all = array_sum(array_column($summary_data, 'out_of_stock'));
        $not_for_sale_all = array_sum(array_column($summary_data, 'not_for_sale'));
        $pending_all = array_sum(array_column($summary_data, 'pending'));
        $checked_all = $total_all - $pending_all;
        $progress_all = $total_all > 0 ? ($checked_all / $total_all) * 100 : 0;
        ?>
        
        <div class="card shadow-lg">
          <div class="card-header bg-dark text-white">
            <h4 class="card-title mb-0">
              <i class="bi bi-bar-chart-fill me-2"></i>สถิติรวม - จังหวัดสมุทรปราการ
            </h4>
          </div>
          <div class="card-body">
            <div class="row text-center mb-4">
              <div class="col-md-2">
                <div class="h2 text-primary mb-0"><?php echo $total_all; ?></div>
                <small class="text-muted">สินค้าทั้งหมด</small>
              </div>
              <div class="col-md-2">
                <div class="h2 text-info mb-0"><?php echo $checked_all; ?></div>
                <small class="text-muted">ตรวจสอบแล้ว</small>
              </div>
              <div class="col-md-2">
                <div class="h2 text-success mb-0"><?php echo $in_stock_all; ?></div>
                <small class="text-muted">มี STOCK</small>
              </div>
              <div class="col-md-2">
                <div class="h2 text-danger mb-0"><?php echo $out_of_stock_all; ?></div>
                <small class="text-muted">สินค้าหมด</small>
              </div>
              <div class="col-md-2">
                <div class="h2 text-secondary mb-0"><?php echo $not_for_sale_all; ?></div>
                <small class="text-muted">ไม่มีขาย</small>
              </div>
              <div class="col-md-2">
                <div class="h2 text-warning mb-0"><?php echo $pending_all; ?></div>
                <small class="text-muted">รอตรวจสอบ</small>
              </div>
            </div>
            
            <div class="progress progress-custom mb-3" style="height: 15px;">
              <?php if ($in_stock_all > 0): ?>
                <div class="progress-bar bg-success" style="width: <?php echo ($in_stock_all / $total_all) * 100; ?>%"></div>
              <?php endif; ?>
              <?php if ($out_of_stock_all > 0): ?>
                <div class="progress-bar bg-danger" style="width: <?php echo ($out_of_stock_all / $total_all) * 100; ?>%"></div>
              <?php endif; ?>
              <?php if ($not_for_sale_all > 0): ?>
                <div class="progress-bar bg-secondary" style="width: <?php echo ($not_for_sale_all / $total_all) * 100; ?>%"></div>
              <?php endif; ?>
              <?php if ($pending_all > 0): ?>
                <div class="progress-bar bg-warning" style="width: <?php echo ($pending_all / $total_all) * 100; ?>%"></div>
              <?php endif; ?>
            </div>
            
            <div class="text-center">
              <h5>ความคืบหน้าการตรวจสอบ: <?php echo number_format($progress_all, 1); ?>%</h5>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
