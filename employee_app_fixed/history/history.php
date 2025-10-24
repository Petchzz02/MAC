<?php
/**
 * history.php
 * หน้าประวัติการทำงาน - แสดงข้อมูลจากการบันทึก checklist
 */

// รวม config เพื่อเชื่อมต่อฐานข้อมูล
require_once __DIR__ . '/../config.php';

// กำหนดค่าสำหรับ header template
$page_title = 'ประวัติการทำงาน';
$current_path = '../';
$extra_css = ['assets/location.css', 'history/history.css'];

// รวม header template
include __DIR__ . '/../includes/header.php';

// ดึงข้อมูลประวัติการทำงานจากฐานข้อมูล
$conn_checklist = getChecklistConnection();
$work_history = [];

if ($conn_checklist) {
    // รายชื่อตารางทั้งหมด
    $tables = ['เมืองสมุทรปราการ', 'พระประแดง', 'พระสมุทรเจดีย์', 'บางพลี', 'บางบ่อ', 'บางเสาธง'];
    
    // สร้าง SQL เพื่อดึงข้อมูลจากทุกตาราง
    $union_sql = [];
    foreach ($tables as $table) {
        $escaped_table = mysqli_real_escape_string($conn_checklist, $table);
        $union_sql[] = "SELECT '$table' as location, DATE(updated_at) as work_date, 
                        MIN(TIME(updated_at)) as first_update, 
                        MAX(TIME(updated_at)) as last_update,
                        COUNT(*) as records_updated
                        FROM `$escaped_table` 
                        WHERE updated_at IS NOT NULL 
                        AND DATE(updated_at) >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
                        GROUP BY DATE(updated_at)";
    }
    
    if (!empty($union_sql)) {
        $final_sql = "SELECT work_date, 
                      MIN(first_update) as check_in_time,
                      MAX(last_update) as check_out_time,
                      COUNT(DISTINCT location) as locations_worked
                      FROM (" . implode(" UNION ALL ", $union_sql) . ") as combined
                      GROUP BY work_date 
                      ORDER BY work_date DESC 
                      LIMIT 30";
        
        $result = mysqli_query($conn_checklist, $final_sql);
        
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                // คำนวณชั่วโมงทำงาน
                $check_in = new DateTime($row['check_in_time']);
                $check_out = new DateTime($row['check_out_time']);
                $interval = $check_in->diff($check_out);
                $work_hours = $interval->h + ($interval->i / 60);
                
                // กำหนดสถานะ
                $status = 'ปกติ';
                $status_class = 'bg-success';
                
                // ตรวจสอบเวลาเข้างาน
                $check_in_hour = (int)$check_in->format('H');
                $check_in_minute = (int)$check_in->format('i');
                $check_in_total_minutes = ($check_in_hour * 60) + $check_in_minute;
                
                if ($check_in_total_minutes > 510) { // หลัง 08:30
                    $status = 'สาย';
                    $status_class = 'bg-warning';
                } elseif ($check_in_total_minutes < 480) { // ก่อน 08:00
                    $status = 'ปกติ';
                    $status_class = 'bg-success';
                }
                
                // ตรวจสอบชั่วโมงทำงาน
                if ($work_hours < 9) {
                    $status = 'ผิดปกติ';
                    $status_class = 'bg-danger';
                }
                
                $work_history[] = [
                    'date' => $row['work_date'],
                    'check_in' => $check_in->format('H:i'),
                    'check_out' => $check_out->format('H:i'),
                    'work_hours' => number_format($work_hours, 2),
                    'locations_worked' => $row['locations_worked'],
                    'status' => $status,
                    'status_class' => $status_class
                ];
            }
        }
    }
    
    // ไม่ต้องปิด connection เองเพราะ Database class จะจัดการให้
}
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
              ประวัติการทำงาน 30 วันล่าสุด (<?php echo count($work_history); ?> วัน)
            </div>
            
            <?php if (empty($work_history)): ?>
              <div class="alert alert-warning text-center" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                ไม่พบข้อมูลการทำงานในช่วง 30 วันที่ผ่านมา
              </div>
            <?php else: ?>
            
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
                  <?php foreach ($work_history as $index => $record): ?>
                  <tr>
                    <th scope="row"><?php echo $index + 1; ?></th>
                    <td><?php echo date('d/m/Y', strtotime($record['date'])); ?></td>
                    <td><?php echo $record['check_in']; ?></td>
                    <td><?php echo $record['check_out']; ?></td>
                    <td><?php echo $record['work_hours']; ?> ชั่วโมง</td>
                    <td><span class="badge <?php echo $record['status_class']; ?>"><?php echo $record['status']; ?></span></td>
                  </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
            
            <?php endif; ?>
            
            <div class="text-center mt-4">
              <a href="../index.php" class="btn btn-outline-primary me-2">
                <i class="bi bi-house-fill me-2"></i>กลับหน้าหลัก
              </a>
              <button class="btn btn-outline-success" onclick="downloadReport()" <?php echo empty($work_history) ? 'disabled' : ''; ?>>
                <i class="bi bi-download me-2"></i>ดาวน์โหลดรายงาน
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

<?php
// กำหนด JavaScript สำหรับดาวน์โหลดรายงาน
$inline_js = '
function downloadReport() {
    // สร้างข้อมูล CSV
    let csvContent = "วันที่,เวลาเข้า,เวลาออก,ชั่วโมงทำงาน,สถานะ\\n";
    
    const rows = document.querySelectorAll("tbody tr");
    rows.forEach(row => {
        const cells = row.querySelectorAll("td");
        if (cells.length >= 5) {
            const date = cells[0].textContent.trim();
            const checkIn = cells[1].textContent.trim();
            const checkOut = cells[2].textContent.trim();
            const workHours = cells[3].textContent.trim();
            const status = cells[4].querySelector(".badge").textContent.trim();
            
            csvContent += `"${date}","${checkIn}","${checkOut}","${workHours}","${status}"\\n`;
        }
    });
    
    // สร้างไฟล์และดาวน์โหลด
    const blob = new Blob([csvContent], { type: "text/csv;charset=utf-8;" });
    const link = document.createElement("a");
    
    if (link.download !== undefined) {
        const url = URL.createObjectURL(blob);
        link.setAttribute("href", url);
        link.setAttribute("download", "work_history_" + new Date().toISOString().slice(0, 10) + ".csv");
        link.style.visibility = "hidden";
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        
        // แสดงข้อความแจ้งเตือน
        showDownloadNotification();
    }
}

function showDownloadNotification() {
    const notification = document.createElement("div");
    notification.className = "alert alert-success alert-dismissible fade show position-fixed";
    notification.style.cssText = `
        top: 20px;
        right: 20px;
        z-index: 9999;
        min-width: 300px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    `;
    notification.innerHTML = `
        <i class="bi bi-check-circle-fill me-2"></i>
        ดาวน์โหลดรายงานสำเร็จ!
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        if (notification.parentNode) {
            notification.remove();
        }
    }, 3000);
}

// เพิ่มสถิติโดยรวม
document.addEventListener("DOMContentLoaded", function() {
    const rows = document.querySelectorAll("tbody tr");
    let totalDays = rows.length;
    let normalDays = 0;
    let lateDays = 0;
    let abnormalDays = 0;
    
    rows.forEach(row => {
        const statusBadge = row.querySelector(".badge");
        if (statusBadge) {
            const status = statusBadge.textContent.trim();
            switch (status) {
                case "ปกติ":
                    normalDays++;
                    break;
                case "สาย":
                    lateDays++;
                    break;
                case "ผิดปกติ":
                    abnormalDays++;
                    break;
            }
        }
    });
    
    // แสดงสถิติ
    if (totalDays > 0) {
        const statsHtml = `
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0"><i class="bi bi-bar-chart-fill me-2"></i>สถิติการทำงาน</h6>
                        </div>
                        <div class="card-body">
                            <div class="row text-center">
                                <div class="col-md-3">
                                    <div class="stat-item">
                                        <div class="stat-number text-primary">${totalDays}</div>
                                        <div class="stat-label">วันทำงานทั้งหมด</div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="stat-item">
                                        <div class="stat-number text-success">${normalDays}</div>
                                        <div class="stat-label">วันปกติ</div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="stat-item">
                                        <div class="stat-number text-warning">${lateDays}</div>
                                        <div class="stat-label">วันสาย</div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="stat-item">
                                        <div class="stat-number text-danger">${abnormalDays}</div>
                                        <div class="stat-label">วันผิดปกติ</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        document.querySelector(".text-center.mt-4").insertAdjacentHTML("beforebegin", statsHtml);
    }
});
';

// รวม footer template
include __DIR__ . '/../includes/footer.php';
?>
