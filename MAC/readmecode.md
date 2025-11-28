# 📋 MAC Employee Checklist System - เอกสารอธิบายโค้ด

## 📑 สารบัญ
1. [โครงสร้างโฟลเดอร์และไฟล์](#โครงสร้างโฟลเดอร์และไฟล์)
2. [ไฟล์หลัก (Core Files)](#ไฟล์หลัก-core-files)
3. [ระบบ Authentication](#ระบบ-authentication)
4. [ระบบสถานที่และ Checklist](#ระบบสถานที่และ-checklist)
5. [ระบบรายงานและสถิติ](#ระบบรายงานและสถิติ)
6. [ระบบประวัติการทำงาน](#ระบบประวัติการทำงาน)
7. [โครงสร้างฐานข้อมูล](#โครงสร้างฐานข้อมูล)

---

## 📂 โครงสร้างโฟลเดอร์และไฟล์

```
MAC/
├── 📄 config.php                    # ไฟล์ตั้งค่าหลัก (Database, Session)
├── 📄 index.php                     # หน้าหลักระบบ (Dashboard)
├── 🔐 login.php                     # หน้า Login Form
├── 🔐 login_db.php                  # ประมวลผลการ Login
├── 🚪 logout.php                    # ออกจากระบบ
├── 🧭 nav.php                       # Navigation Bar
├── 📊 reports.php                   # หน้ารายงานและสถิติ
├── 📖 README.md                     # เอกสารโปรเจกต์
│
├── 📁 assets/                       # ไฟล์ CSS และ JavaScript
│   ├── index.css                    # สไตล์หน้า Dashboard
│   ├── login.css                    # สไตล์หน้า Login
│   ├── login.js                     # JavaScript สำหรับ Login
│   ├── nav.css                      # สไตล์ Navigation
│   └── reports.css                  # สไตล์หน้ารายงาน
│
├── 📁 exports/                      # ไฟล์ SQL Export (Auto-generated)
│   ├── checklist_export_*.sql       # Backup ข้อมูล Checklist
│
├── 📁 history/                      # ระบบประวัติการทำงาน
│   ├── history.php                  # หน้าประวัติการทำงาน
│   └── history.css                  # สไตล์หน้าประวัติ
│
├── 📁 image/                        # รูปภาพสินค้า
│   ├── MAC.png                      # โลโก้บริษัท
│   └── products/                    # รูปสินค้า (แยกตามหมวดหมู่)
│       ├── snack/                   # รูปสินค้าประเภทขนม
│       └── water/                   # รูปสินค้าประเภทเครื่องดื่ม
│
├── 📁 includes/                     # ไฟล์ Template และ Helper
│   ├── database_helper.php          # Database Management Class
│   ├── header.php                   # Template Header
│   └── footer.php                   # Template Footer
│
├── 📁 location/                     # ระบบเลือกสถานที่
│   ├── location.php                 # หน้าเลือกสถานที่
│   └── assets/
│       └── location.css             # สไตล์หน้าสถานที่
│   └── checklist/                   # ระบบ Checklist
│       ├── checklist.php            # หน้า Checklist หลัก
│       ├── save.php                 # บันทึกข้อมูล Checklist
│       ├── summary.php              # สรุปผล Checklist
│       ├── clear_data.php           # ล้างข้อมูล Checklist
│       ├── add.php                  # เพิ่มสินค้าใหม่
│       ├── test_sql.php             # ทดสอบ SQL Query
│       └── checklist.css            # สไตล์ Checklist
│
└── 📁 sql/                          # ไฟล์ SQL สำหรับสร้างฐานข้อมูล
    ├── db_employee.sql              # โครงสร้างตาราง employees
    └── products_complete.sql        # ข้อมูลสินค้าเริ่มต้น
```

---

## 🔧 ไฟล์หลัก (Core Files)

### 1️⃣ **config.php** - ไฟล์ตั้งค่าระบบหลัก

```php
// หน้าที่หลัก:
- โหลด DatabaseHelper class
- สร้าง global database connection ($conn, $db)
- เริ่ม session อัตโนมัติ
- จัดการ charset เป็น utf8mb4 (รองรับภาษาไทย)
```

**การทำงาน:**
1. เปิด error reporting (สำหรับ development)
2. โหลด `includes/database_helper.php`
3. สร้าง Singleton instance ของ Database
4. ตั้งค่า backward compatibility สำหรับโค้ดเก่า
5. เริ่ม PHP session

**ฟังก์ชันสำคัญ:**
- `getChecklistConnection()`: ดึง connection ไปยังฐานข้อมูล checklist
- `isValidLocation($location)`: ตรวจสอบว่าชื่อสถานที่ถูกต้องหรือไม่
- `getValidLocations()`: ดึงรายชื่อสถานที่ทั้งหมด

---

### 2️⃣ **includes/database_helper.php** - Database Management Class

```php
// Design Pattern: Singleton
// ป้องกันการสร้าง connection ซ้ำซ้อน
```

**คุณสมบัติสำคัญ:**

#### 🔹 Multi-Database Support
- `db_employee`: ฐานข้อมูลพนักงาน
- `db_sp_checklist`: ฐานข้อมูล checklist แยกตามสถานที่

#### 🔹 Environment Management
```php
const ENVIRONMENT = 'local';  // หรือ 'production'
```
- **Local**: ใช้ localhost, root, ไม่มีรหัสผ่าน
- **Production**: ใช้ InfinityFree hosting credentials

#### 🔹 Connection Pooling
- ตรวจสอบ connection ก่อนใช้งานด้วย `ping()`
- สร้าง connection ใหม่เฉพาะเมื่อจำเป็น
- ปิด connection อัตโนมัติใน destructor

#### 🔹 Security Features
- Prepared statements support
- Input validation สำหรับชื่อสถานที่
- charset utf8mb4 (ป้องกัน SQL injection แบบ multi-byte)

**เมธอดสำคัญ:**
```php
getInstance()                          // รับ Singleton instance
getEmployeeConnection()                // Connection ฐานข้อมูลพนักงาน
getChecklistConnection()               // Connection ฐานข้อมูล checklist
isValidLocation($location)             // Validate ชื่อสถานที่
getProductsByLocation($location)       // ดึงสินค้าทั้งหมดของสถานที่
updateProductStatus(...)               // อัปเดตสถานะสินค้า
closeConnections()                     // ปิด connections ทั้งหมด
```

---

### 3️⃣ **includes/header.php** - Header Template

**การทำงาน:**

1. **Authentication Check**
   - ตรวจสอบ session user
   - Redirect ไปหน้า login หากไม่ได้ login
   - ยกเว้นเมื่อกำหนด `$skip_auth = true`

2. **Path Management**
   - รองรับหลาย level ของ directory
   - Auto-adjust path สำหรับ assets และ links
   - ใช้ `$current_path` สำหรับ relative path

3. **Template Variables**
   ```php
   $page_title     // หัวเรื่องหน้า
   $current_path   // Path prefix (../หรือ../../)
   $include_nav    // แสดง navigation หรือไม่
   $extra_css      // Array ของ CSS files
   $extra_head     // HTML เพิ่มเติมใน <head>
   ```

4. **Include Libraries**
   - Bootstrap 5.0.2
   - Bootstrap Icons
   - Font Awesome
   - Chart.js

**ตัวอย่างการใช้:**
```php
$page_title = 'หน้าหลัก MAC';
$current_path = '';
$extra_css = ['assets/index.css'];
include __DIR__ . '/includes/header.php';
```

---

### 4️⃣ **includes/footer.php** - Footer Template

**การทำงาน:**
- ปิด tag `</main>`, `</body>`, `</html>`
- โหลด Bootstrap JavaScript
- รองรับ JavaScript เพิ่มเติม

**Template Variables:**
```php
$extra_js       // Array ของ JS files
$inline_js      // JavaScript code แบบ inline
```

**ตัวอย่าง:**
```php
$inline_js = '
    console.log("Page loaded");
    // Your JavaScript code here
';
include __DIR__ . '/includes/footer.php';
```

---

## 🔐 ระบบ Authentication

### 🔹 **login.php** - Login Form

**การทำงาน:**
1. ตรวจสอบว่า user login อยู่หรือไม่
   - ถ้าใช่: redirect ไป index.php
2. แสดงฟอร์ม login
3. แสดง error message จาก parameter `?error=`

**Error Codes:**
- `error=1`: รหัสพนักงานหรือรหัสผ่านไม่ถูกต้อง
- `error=2`: เกิดข้อผิดพลาดภายในระบบ
- `error=3`: กรุณาเข้าสู่ระบบก่อน

**ฟอร์ม Fields:**
```html
<input name="employee_id">  // รหัสพนักงาน
<input name="password">     // รหัสผ่าน
```

**Client-side Validation:**
- ใช้ `login.js` สำหรับ validation
- ป้องกัน form submission หากข้อมูลไม่ครบ

---

### 🔹 **login_db.php** - Login Processing

**Security Features:**

1. **POST Method Only**
   ```php
   if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
       header('Location: login.php'); exit;
   }
   ```

2. **Input Validation**
   ```php
   $employee_id = trim($_POST['employee_id']);
   if ($employee_id === '' || $password === '') {
       // Error
   }
   ```

3. **Prepared Statements**
   ```php
   $sql = "SELECT ... WHERE employee_id = ? LIMIT 1";
   $stmt = mysqli_prepare($conn, $sql);
   mysqli_stmt_bind_param($stmt, 's', $employee_id);
   ```

4. **Password Verification**
   - รองรับ password hashing (password_verify)
   - Auto-migrate plain text → hash

5. **Session Security**
   ```php
   session_regenerate_id(true);  // ป้องกัน session fixation
   ```

**การทำงาน:**
1. รับค่า employee_id และ password
2. Query ฐานข้อมูลด้วย prepared statement
3. ตรวจสอบรหัสผ่าน
   - ถ้าเป็น hash: ใช้ password_verify()
   - ถ้าเป็น plain text: เปรียบเทียบตรง + auto-migrate เป็น hash
4. สร้าง session และ redirect ไป index.php

**Session Data Structure:**
```php
$_SESSION['user'] = [
    'id' => 1,
    'employee_id' => 'EMP001',
    'fname' => 'สมชาย',
    'lname' => 'ใจดี',
    'name' => 'สมชาย ใจดี',
    'email' => 'somchai@example.com'
];
```

---

### 🔹 **logout.php** - Logout System

**การทำงาน:**
1. ล้างข้อมูล session: `$_SESSION = []`
2. ลบ cookie session
3. ทำลาย session: `session_destroy()`
4. Redirect กลับไปหน้า login

**Code Flow:**
```php
$_SESSION = [];                      // Clear session data
setcookie(session_name(), '', ...);  // Delete session cookie
session_destroy();                   // Destroy session
header('Location: login.php');       // Redirect
```

---

## 📍 ระบบสถานที่และ Checklist

### 🔹 **location/location.php** - เลือกสถานที่ทำงาน

**การทำงาน:**

1. **กำหนดรายชื่อสถานที่**
   ```php
   $locations = [
       ['name' => 'เมืองสมุทรปราการ', 'description' => '...', 'icon' => 'bi-geo-alt-fill'],
       ['name' => 'พระประแดง', ...],
       // ... อื่นๆ
   ];
   ```

2. **ดึงสถิติจากฐานข้อมูล**
   - นับจำนวนสินค้าทั้งหมด
   - นับจำนวนที่ตรวจสอบแล้ว
   - คำนวณงานที่รอตรวจสอบ

3. **แสดง Location Cards**
   - แต่ละการ์ดแสดงสถิติของสถานที่
   - Link ไปหน้า checklist

4. **สถิติรวม**
   - จำนวนสถานที่ทั้งหมด
   - รายการสินค้ารวม
   - ตรวจสอบแล้ว / รอตรวจสอบ

**JavaScript Features:**
- Hover effects บนการ์ด
- Animated counter
- Click ripple effect
- Scroll reveal animation

---

### 🔹 **location/checklist/checklist.php** - Checklist หลัก

**Security & Validation:**

1. **ตรวจสอบ Location**
   ```php
   $locations = ['เมืองสมุทรปราการ', 'พระประแดง', ...];
   if (!in_array($location, $locations, true)) {
       header('Location: ../location.php'); exit;
   }
   ```

2. **ป้องกัน SQL Injection**
   ```php
   $safe_table_name = mysqli_real_escape_string($conn, $table);
   ```

3. **ตรวจสอบตารางมีอยู่จริง**
   ```php
   $tables_query = "SHOW TABLES";
   // ตรวจสอบว่า table name อยู่ในผลลัพธ์
   ```

**การแสดงผล:**

1. **จัดกลุ่มตามหมวดหมู่**
   - เครื่องดื่ม
   - ขนม

2. **แต่ละสินค้าแสดง:**
   - รูปภาพสินค้า
   - ชื่อและรหัสสินค้า
   - Radio buttons สำหรับสถานะ:
     - ✅ มี STOCK
     - ❌ สินค้าหมด
     - ⊖ ไม่มีขาย
   - ช่องหมายเหตุ (required)

3. **Status Card Styling**
   ```php
   'status-in_stock'      // สีเขียว
   'status-out_of_stock'  // สีแดง
   'status-not_for_sale'  // สีเทา
   'status-none'          // ไม่มีสี
   ```

**Buttons:**
- 💾 **บันทึก**: บันทึกข้อมูลไป save.php
- 📊 **ดูสรุปผล**: ไปหน้า summary.php
- 🗑️ **ลบข้อมูล**: ลบข้อมูลทั้งหมด (clear_data.php)
- ❌ **ยกเลิก**: กลับไปหน้า location

**JavaScript Validation:**
```javascript
// ตรวจสอบว่ากรอกหมายเหตุครบทุกรายการ
noteInputs.forEach(input => {
    if (!input.value.trim()) {
        isValid = false;
        input.classList.add('is-invalid');
    }
});
```

---

### 🔹 **location/checklist/save.php** - บันทึกข้อมูล

**Input Data:**
```php
$_POST['location']       // ชื่อสถานที่
$_POST['status'][id]     // Array สถานะ [id => 'in_stock']
$_POST['note'][id]       // Array หมายเหตุ [id => 'หมายเหตุ']
```

**Transaction Management:**
```php
mysqli_begin_transaction($conn);
try {
    // Update data
    mysqli_commit($conn);
} catch (Exception $e) {
    mysqli_rollback($conn);
}
```

**การทำงาน:**

1. **Validate Input**
   - ตรวจสอบ location
   - ตรวจสอบว่า status และ note ไม่เป็นค่าว่าง

2. **ตรวจสอบตารางในฐานข้อมูล**
   ```php
   $tables_query = "SHOW TABLES";
   // ตรวจสอบว่า table มีอยู่จริง
   ```

3. **อัปเดตข้อมูลทีละรายการ**
   ```php
   $sql = "UPDATE `table` SET 
           status = ?, note = ?, updated_at = NOW() 
           WHERE id = ?";
   mysqli_stmt_bind_param($stmt, 'ssi', $status, $note, $id);
   ```

4. **สร้าง SQL Export**
   - เรียกฟังก์ชัน `createSqlExport()`
   - สร้างไฟล์ backup ใน folder `exports/`

5. **Redirect กลับ checklist**
   ```php
   $_SESSION['message'] = "บันทึกข้อมูลเรียบร้อยแล้ว (X รายการ)";
   header('Location: checklist.php?location=...');
   ```

**SQL Export Function:**
```php
function createSqlExport($conn, $location, $table) {
    $exportFile = 'exports/checklist_export_' . date('Y-m-d_H-i-s') . '.sql';
    // สร้าง SQL INSERT statements
    file_put_contents($exportFile, $sqlContent);
}
```

---

### 🔹 **location/checklist/summary.php** - สรุปผล Checklist

**การทำงาน:**

1. **ดึงข้อมูลสรุป**
   ```sql
   SELECT category,
          COUNT(*) as total,
          SUM(CASE WHEN status = 'in_stock' THEN 1 ELSE 0 END) as in_stock,
          SUM(CASE WHEN status = 'out_of_stock' THEN 1 ELSE 0 END) as out_of_stock
   FROM table
   GROUP BY category
   ```

2. **คำนวณเปอร์เซ็นต์**
   - อัตราการตรวจสอบ
   - อัตราสินค้ามี stock
   - การกระจายตามสถานะ

3. **แสดงผลเป็น:**
   - ตารางสรุปตามหมวดหมู่
   - Progress bars
   - กราฟวงกลม (Pie chart)

---

### 🔹 **location/checklist/clear_data.php** - ล้างข้อมูล

**การทำงาน:**
```php
UPDATE `table` SET 
    status = NULL,
    note = NULL,
    updated_at = NULL
WHERE 1=1
```

**Security:**
- ตรวจสอบ POST method
- Validate location
- ใช้ transaction

---

## 📊 ระบบรายงาน

### 🔹 **reports.php** - รายงานและสถิติ

**ข้อมูลที่แสดง:**

#### 1. สถิติภาพรวม (Overview Statistics)
```php
$total_statistics = [
    'total_locations'     // จำนวนสถานที่ทั้งหมด
    'total_products'      // สินค้าทั้งหมด
    'total_checked'       // ตรวจสอบแล้ว
    'total_in_stock'      // มี STOCK
    'total_out_of_stock'  // สินค้าหมด
    'total_pending'       // รอตรวจสอบ
    'check_rate'          // % การตรวจสอบ
    'stock_rate'          // % สินค้ามี stock
];
```

#### 2. สถิติตามหมวดหมู่
```php
$category_statistics = [
    'เครื่องดื่ม' => [
        'total' => 0,
        'checked' => 0,
        'in_stock' => 0,
        // ...
    ],
    'ขนม' => [...],
];
```

#### 3. รายละเอียดแต่ละสถานที่
- ตารางแสดงข้อมูลทุกสถานที่
- Progress bars สำหรับอัตราการตรวจสอบ
- ลิงก์ไปยังหน้า checklist และ summary

#### 4. กิจกรรมล่าสุด
```php
$recent_activities = [
    [
        'location' => 'เมืองสมุทรปราการ',
        'product_name' => 'น้ำดื่ม',
        'status' => 'in_stock',
        'updated_at' => '2025-11-28 10:30:00'
    ],
    // ...
];
```

**กราฟ (Charts):**

1. **กราฟแท่ง** - อัตราการตรวจสอบตามสถานที่
   ```javascript
   new Chart(ctx, {
       type: 'bar',
       data: {
           labels: locationNames,
           datasets: [{data: checkRates}]
       }
   });
   ```

2. **กราฟวงกลม** - การกระจายสถานะสินค้า
   ```javascript
   new Chart(ctx, {
       type: 'doughnut',
       data: {
           labels: ['มี Stock', 'สินค้าหมด', 'ไม่มีขาย', 'รอตรวจสอบ'],
           datasets: [{data: [in_stock, out, not_sale, pending]}]
       }
   });
   ```

**SQL Query หลัก:**
```sql
SELECT 
    COUNT(*) as total_products,
    SUM(CASE WHEN status = 'in_stock' THEN 1 ELSE 0 END) as in_stock,
    SUM(CASE WHEN status = 'out_of_stock' THEN 1 ELSE 0 END) as out_of_stock,
    SUM(CASE WHEN status = 'not_for_sale' THEN 1 ELSE 0 END) as not_for_sale,
    MAX(updated_at) as last_updated
FROM `location_table`
```

---

## 📜 ระบบประวัติ

### 🔹 **history/history.php** - ประวัติการทำงาน

**การทำงาน:**

1. **ดึงข้อมูลจากทุกสถานที่**
   ```sql
   SELECT location, DATE(updated_at) as work_date,
          MIN(TIME(updated_at)) as first_update,
          MAX(TIME(updated_at)) as last_update,
          COUNT(*) as records_updated
   FROM table
   GROUP BY DATE(updated_at)
   ORDER BY work_date DESC
   LIMIT 30
   ```

2. **คำนวณชั่วโมงทำงาน**
   ```php
   $check_in = new DateTime($first_update);
   $check_out = new DateTime($last_update);
   $work_hours = $check_in->diff($check_out);
   ```

3. **กำหนดสถานะ**
   - **ปกติ**: เข้างานตรงเวลา, ทำงานครบชั่วโมง
   - **สาย**: เข้างานหลัง 08:30
   - **ผิดปกติ**: ทำงานไม่ถึง 9 ชั่วโมง

**แสดงผล:**
- ตารางประวัติ 30 วันล่าสุด
- สถิติรวม (วันปกติ, วันสาย, วันผิดปกติ)
- ปุ่มดาวน์โหลดรายงาน CSV

**Download Report:**
```javascript
function downloadReport() {
    let csvContent = "วันที่,เวลาเข้า,เวลาออก,ชั่วโมงทำงาน,สถานะ\n";
    // ... สร้าง CSV
    const blob = new Blob([csvContent], {type: "text/csv"});
    // ... ดาวน์โหลด
}
```

---

## 💾 ฐานข้อมูล

### Database Schema

#### 🗄️ **db_employee** - ฐานข้อมูลพนักงาน

**ตาราง: employees**
```sql
CREATE TABLE employees (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    employee_id VARCHAR(50) UNIQUE NOT NULL,  -- รหัสพนักงาน
    fname VARCHAR(100) NOT NULL,              -- ชื่อ
    lname VARCHAR(100) NOT NULL,              -- นามสกุล
    email VARCHAR(100),                       -- อีเมล
    Password VARCHAR(255) NOT NULL,           -- รหัสผ่าน (hashed)
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

#### 🗄️ **db_sp_checklist** - ฐานข้อมูล Checklist

**ตารางตามสถานที่** (6 ตาราง):
- `เมืองสมุทรปราการ`
- `พระประแดง`
- `พระสมุทรเจดีย์`
- `บางพลี`
- `บางบ่อ`
- `บางเสาธง`

**โครงสร้างตาราง:**
```sql
CREATE TABLE `location_name` (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_code VARCHAR(50) UNIQUE NOT NULL,     -- รหัสสินค้า
    product_name VARCHAR(200) NOT NULL,           -- ชื่อสินค้า
    category ENUM('เครื่องดื่ม', 'ขนม') NOT NULL,-- หมวดหมู่
    image_path VARCHAR(255),                      -- Path รูปภาพ
    status ENUM('in_stock', 'out_of_stock', 'not_for_sale') NULL,  -- สถานะ
    note TEXT NULL,                               -- หมายเหตุ
    updated_at TIMESTAMP NULL,                    -- เวลาอัปเดต
    updated_by VARCHAR(100) NULL,                 -- ผู้อัปเดต
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

**Indexes:**
```sql
CREATE INDEX idx_product_code ON location_name(product_code);
CREATE INDEX idx_category ON location_name(category);
CREATE INDEX idx_status ON location_name(status);
CREATE INDEX idx_updated_at ON location_name(updated_at);
```



---

## 🔄 Flow การทำงานของระบบ

### 1. Login Flow
```
User → login.php 
  ↓ (กรอก employee_id, password)
login_db.php → ตรวจสอบจาก db_employee
  ↓ (password_verify)
สร้าง $_SESSION['user']
  ↓ (session_regenerate_id)
Redirect → index.php
```

### 2. Checklist Flow
```
User → location.php (เลือกสถานที่)
  ↓
checklist.php?location=xxx
  ↓ (Query ข้อมูลสินค้า)
แสดงฟอร์ม Checklist
  ↓ (User กรอกข้อมูล)
save.php
  ↓ (mysqli_begin_transaction)
UPDATE ข้อมูลทีละรายการ
  ↓ (mysqli_commit)
createSqlExport() → สร้างไฟล์ backup
  ↓
Redirect → checklist.php (พร้อม success message)
```

### 3. Report Flow
```
User → reports.php
  ↓
Query ข้อมูลจากทุกสถานที่ (6 ตาราง)
  ↓
คำนวณสถิติ (total, checked, rates)
  ↓
จัดกลุ่มตามหมวดหมู่
  ↓
สร้างกราฟด้วย Chart.js (Bar, Doughnut)
  ↓
แสดงผลแบบ Real-time
```

---
