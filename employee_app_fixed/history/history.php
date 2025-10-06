<?php
// กำหนดค่าสำหรับ header template
$page_title = 'ประวัติการทำงาน';
$current_path = '../';
$extra_css = ['assets/location.css'];

// รวม header template
include __DIR__ . '/../includes/header.php';
?>
  <div class="container mt-4">
    <div class="row justify-content-center">
      <div class="col-md-10">
        <div class="card shadow-lg">
          <div class="card-header bg-success text-white">
            <h2 class="card-title mb-0">
              <i class="bi bi-clock-history me-2"></i>ประวัติการทำงาน
            </h2>
          </div>
          <div class="card-body">
            <div class="alert alert-info" role="alert">
              <i class="bi bi-info-circle-fill me-2"></i>
              หน้านี้เป็นเพจตัวอย่างที่ต้องล็อกอินก่อนเข้าถึง
            </div>
            
            <div class="table-responsive">
              <table class="table table-striped table-hover">
                <thead class="table-dark">
                  <tr>
                    <th scope="col">#</th>
                    <th scope="col">วันที่</th>
                    <th scope="col">เวลาเข้า</th>
                    <th scope="col">เวลาออก</th>
                    <th scope="col">ชั่วโมงทำงาน</th>
                    <th scope="col">สถานะ</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <th scope="row">1</th>
                    <td>07/09/2025</td>
                    <td>08:00</td>
                    <td>17:00</td>
                    <td>9 ชั่วโมง</td>
                    <td><span class="badge bg-success">สำเร็จ</span></td>
                  </tr>
                  <tr>
                    <th scope="row">2</th>
                    <td>06/09/2025</td>
                    <td>08:15</td>
                    <td>17:00</td>
                    <td>8.75 ชั่วโมง</td>
                    <td><span class="badge bg-warning">สาย</span></td>
                  </tr>
                  <tr>
                    <th scope="row">3</th>
                    <td>05/09/2025</td>
                    <td>08:00</td>
                    <td>17:00</td>
                    <td>9 ชั่วโมง</td>
                    <td><span class="badge bg-success">สำเร็จ</span></td>
                  </tr>
                </tbody>
              </table>
            </div>
            
            <div class="text-center mt-4">
              <a href="../index.php" class="btn btn-outline-primary me-2">
                <i class="bi bi-house-fill me-2"></i>กลับหน้าหลัก
              </a>
              <button class="btn btn-outline-success">
                <i class="bi bi-download me-2"></i>ดาวน์โหลดรายงาน
              </button>
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
