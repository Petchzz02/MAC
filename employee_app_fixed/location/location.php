<?php
// กำหนดค่าสำหรับ header template
$page_title = 'Location';
$current_path = '../';
$extra_css = ['assets/location.css'];
$extra_head = '
  <style>
    .location-card {
      transition: all 0.3s ease;
      cursor: pointer;
    }
    .location-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 30px rgba(0,0,0,0.15);
    }
    .card-header {
      background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
      border: none;
    }
    .btn-primary {
      background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
      border: none;
    }
    .btn-primary:hover {
      background: linear-gradient(135deg, #0056b3 0%, #004085 100%);
      transform: translateY(-1px);
    }
  </style>';

// รวม header template
include __DIR__ . '/../includes/header.php';
?>
  <div class="container mt-4">
    <div class="row justify-content-center">
      <div class="col-md-10">
        <div class="card shadow-lg">
          <div class="card-header bg-primary text-white">
            <h2 class="card-title mb-0">
              เลือกเขตพื้นที่ทำงาน
            </h2>
          </div>
          <div class="card-body">
            <div class="alert alert-info" role="alert">
              เลือกเขตพื้นที่ในจังหวัดสมุทรปราการที่ต้องการทำงาน
            </div>
            
            <div class="row">
              <?php
              $locations = [
                ['name' => 'เมืองสมุทรปราการ', 'color' => 'primary'],
                ['name' => 'พระประแดง', 'color' => 'success'],
                ['name' => 'พระสมุทรเจดีย์', 'color' => 'info'],
                ['name' => 'บางพลี', 'color' => 'warning'],
                ['name' => 'บางบ่อ', 'color' => 'secondary'],
                ['name' => 'บางเสาธง', 'color' => 'danger']
              ];
              
              foreach ($locations as $location) {
                $safeName = urlencode($location['name']);
                echo '
                <div class="col-md-6 col-lg-4 mb-3">
                  <div class="card border-'.$location['color'].' h-100 location-card">
                    <div class="card-body text-center d-flex flex-column">
                      <h5 class="card-title text-'.$location['color'].' mt-3">'.$location['name'].'</h5>
                      <div class="mt-auto">
                        <a href="checklist/checklist.php?location='.$safeName.'" class="btn btn-'.$location['color'].' w-100">
                          เข้าสู่เช็คลิสต์
                        </a>
                      </div>
                    </div>
                  </div>
                </div>';
              }
              ?>
            </div>
            
            <div class="text-center mt-4">
              <a href="checklist/summary.php" class="btn btn-info me-2">
                สรุปผลการตรวจสอบ
              </a>
              <a href="../index.php" class="btn btn-outline-primary">
                กลับหน้าหลัก
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

<?php
// รวม footer template
include __DIR__ . '/../includes/footer.php';
?>
