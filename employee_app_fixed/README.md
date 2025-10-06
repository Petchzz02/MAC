# ระบบพนักงาน MAC - Employee Management System

## คำอธิบายโปรเจกต์
ระบบจัดการพนักงานสำหรับบริษัท MAC ที่ประกอบด้วยระบบเข้าสู่ระบบ การจัดการสถานที่ทำงาน และระบบเช็คลิสต์สินค้า

## ฟีเจอร์หลัก
- 🔐 ระบบเข้าสู่ระบบที่ปลอดภัย (Password Hashing)
- 🏢 เลือกสถานที่ทำงาน (6 สาขา)
- ✅ ระบบเช็คลิสต์สินค้า
- 📊 บันทึกข้อมูลและสร้าง Export
- 🧹 ฟีเจอร์เคลียร์ข้อมูลล่าสุด
- 📱 Responsive Design
- 🎨 Modern UI/UX

## โครงสร้างไฟล์
```
employee_app_fixed/
├── assets/                    # ไฟล์ CSS และ JavaScript
│   ├── index.css
│   ├── login.css
│   ├── login.js
│   └── nav.css
├── exports/                   # ไฟล์ SQL Export
├── history/                   # หน้าประวัติการทำงาน
│   └── history.php
├── image/                     # รูปภาพโลโก้
│   └── MAC.png
├── includes/                  # Template ส่วนกลาง
│   ├── header.php
│   └── footer.php
├── location/                  # จัดการสถานที่
│   ├── location.php
│   ├── assets/
│   │   └── location.css
│   └── checklist/             # ระบบเช็คลิสต์
│       ├── checklist.php
│       ├── checklist.css
│       ├── save.php
│       ├── clear_latest.php
│       ├── clear_data.php
│       ├── summary.php
│       └── add.php
├── logs/                      # Log files
├── sql/                       # ไฟล์ฐานข้อมูล
│   ├── db_employee.sql
│   └── samutprakan_checklist.sql
├── uploads/                   # ไฟล์อัปโหลด
│   └── photos/
├── config.php                 # การตั้งค่าฐานข้อมูล
├── index.php                  # หน้าหลัก
├── login.php                  # หน้าเข้าสู่ระบบ
├── login_db.php              # ประมวลผลการเข้าสู่ระบบ
├── logout.php                # ออกจากระบบ
├── nav.php                   # เมนูนำทาง
└── README.md                 # คู่มือการใช้งาน
```

## ความต้องการของระบบ
- **Web Server**: Apache (XAMPP, WAMP, LAMP)
- **PHP**: เวอร์ชัน 7.4 หรือสูงกว่า
- **MySQL**: เวอร์ชัน 5.7 หรือสูงกว่า
- **Extension**: mysqli, session

## การติดตั้ง

### 1. ติดตั้ง XAMPP
1. ดาวน์โหลด XAMPP จาก https://www.apachefriends.org/
2. ติดตั้งและเรียกใช้ Apache และ MySQL

### 2. ตั้งค่าฐานข้อมูล
1. เปิด phpMyAdmin (http://localhost/phpmyadmin)
2. สร้างฐานข้อมูล 2 ฐาน:
   - `db_employee` - สำหรับข้อมูลพนักงาน
   - `db_sp_checklist` - สำหรับข้อมูลเช็คลิสต์

3. Import ไฟล์ SQL:
   ```sql
   -- Import ลงใน db_employee
   sql/db_employee.sql
   
   -- Import ลงใน db_sp_checklist  
   sql/samutprakan_checklist.sql
   ```

### 3. ตั้งค่าไฟล์
1. วางโฟลเดอร์ `employee_app_fixed` ไว้ใน `c:\xampp\htdocs\`
2. แก้ไขไฟล์ `config.php` หากจำเป็น:
   ```php
   $DB_HOST = 'localhost';
   $DB_USER = 'root'; 
   $DB_PASS = '';  // ใส่รหัสผ่าน MySQL ถ้ามี
   ```

### 4. สิทธิ์โฟลเดอร์
ตั้งค่าสิทธิ์การเขียนไฟล์:
- `exports/` - สำหรับไฟล์ SQL Export
- `logs/` - สำหรับ Log files
- `uploads/` - สำหรับรูปภาพ

## การใช้งาน

### 1. เข้าสู่ระบบ
- เปิด: http://localhost/employee_app_fixed/
- ใช้รหัสพนักงานและรหัสผ่านเข้าสู่ระบบ

### 2. เลือกสถานที่
- เลือกสาขาที่ต้องการตรวจสอบ (6 สาขา)
- คลิก "เข้าสู่เช็คลิสต์"

### 3. เช็คลิสต์สินค้า
- ตรวจสอบสถานะสินค้า: มี STOCK / สินค้าหมด / ไม่มีขาย
- ใส่หมายเหตุ (บังคับ)
- คลิก "บันทึกสถานะทั้งหมด"

### 4. ฟีเจอร์เพิ่มเติม
- **เคลียร์ข้อมูลล่าสุด**: ลบข้อมูลที่บันทึกครั้งล่าสุด
- **ดูสรุปผล**: ดูสถิติการตรวจสอบ
- **ประวัติการทำงาน**: ดูประวัติการทำงาน

## ฟีเจอร์ด้านความปลอดภัย
- ✅ Password Hashing (password_hash/password_verify)
- ✅ Prepared Statements (SQL Injection Protection)
- ✅ Session Management
- ✅ Input Validation & Sanitization
- ✅ CSRF Protection (Session-based)
- ✅ Auto Password Migration (Plain to Hash)

## การ Backup และ Export
- ระบบสร้างไฟล์ SQL Export อัตโนมัติเมื่อบันทึกข้อมูล
- ไฟล์ Export จะถูกเก็บในโฟลเดอร์ `exports/`
- Log การเคลียร์ข้อมูลเก็บในโฟลเดอร์ `logs/`

## การแก้ปัญหาเบื้องต้น

### 1. ไม่สามารถเข้าสู่ระบบได้
- ตรวจสอบข้อมูลผู้ใช้ในตาราง `employees`
- ตรวจสอบการเชื่อมต่อฐานข้อมูล

### 2. ข้อผิดพลาดฐานข้อมูล
- ตรวจสอบการตั้งค่าใน `config.php`
- ตรวจสอบว่า MySQL Service ทำงาน

### 3. ไม่สามารถบันทึกได้
- ตรวจสอบสิทธิ์โฟลเดอร์ `exports/` และ `logs/`
- ตรวจสอบ error ใน PHP error log

## การพัฒนาต่อ
- เพิ่มระบบการอัปโหลดรูปภาพ
- ระบบรายงานขั้นสูง
- การแจ้งเตือนผ่าน Email
- Mobile App Integration
- API สำหรับระบบภายนอก

## ผู้พัฒนา
- ระบบจัดการพนักงาน MAC
- อัปเดต: ตุลาคม 2025

## การสนับสนุน
หากพบปัญหาการใช้งาน กรุณาติดต่อฝ่าย IT หรือสร้าง Issue ใน Repository