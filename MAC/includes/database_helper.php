<?php
/**
 * database_helper.php
 * คลาสช่วยจัดการการเชื่อมต่อฐานข้อมูล
 * - รองรับการเชื่อมต่อหลายฐานข้อมูล (db_employee, db_sp_checklist)
 * - ใช้ Singleton pattern เพื่อป้องกันการเชื่อมต่อซ้ำ
 * - รองรับ charset utf8mb4 สำหรับภาษาไทย
 */

class Database {
    private static ?Database $instance = null;
    private ?mysqli $employeeConnection = null;
    private ?mysqli $checklistConnection = null;
    
    // ตั้งค่า environment: 'local' หรือ 'production'
    // เปลี่ยนเป็น 'production' เมื่อต้องการ deploy
    private const ENVIRONMENT = 'local';
    
    // การตั้งค่าฐานข้อมูลสำหรับ localhost
    private const LOCAL_DB_HOST = 'localhost';
    private const LOCAL_DB_USER = 'root';
    private const LOCAL_DB_PASS = '';
    private const LOCAL_DB_EMPLOYEE = 'db_employee';
    private const LOCAL_DB_CHECKLIST = 'db_sp_checklist';
    
    // การตั้งค่าฐานข้อมูลสำหรับ production (InfinityFree)
    private const PROD_DB_HOST = 'sql100.infinityfree.com';
    private const PROD_DB_USER = 'if0_40531565';
    private const PROD_DB_PASS = 'WDPF5eTSQY';
    private const PROD_DB_EMPLOYEE = 'if0_40531565_mysite';
    private const PROD_DB_CHECKLIST = 'if0_40531565_mysite';
    
    private const DB_CHARSET = 'utf8mb4';
    
    // รายชื่อสถานที่ที่ถูกต้อง
    private const VALID_LOCATIONS = [
        'เมืองสมุทรปราการ',
        'พระประแดง', 
        'พระสมุทรเจดีย์',
        'บางพลี',
        'บางบ่อ',
        'บางเสาธง'
    ];

    private function __construct() {
        // Private constructor เพื่อป้องกันการสร้าง instance ใหม่
    }

    /**
     * รับค่า config ตาม environment ปัจจุบัน
     */
    private function getConfig(string $key): string {
        $isProduction = self::ENVIRONMENT === 'production';
        
        return match($key) {
            'host' => $isProduction ? self::PROD_DB_HOST : self::LOCAL_DB_HOST,
            'user' => $isProduction ? self::PROD_DB_USER : self::LOCAL_DB_USER,
            'pass' => $isProduction ? self::PROD_DB_PASS : self::LOCAL_DB_PASS,
            'employee' => $isProduction ? self::PROD_DB_EMPLOYEE : self::LOCAL_DB_EMPLOYEE,
            'checklist' => $isProduction ? self::PROD_DB_CHECKLIST : self::LOCAL_DB_CHECKLIST,
            default => ''
        };
    }

    /**
     * รับ instance เดียวของ Database class
     */
    public static function getInstance(): Database {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * ตรวจสอบว่าการเชื่อมต่อยังใช้งานได้อยู่หรือไม่
     */
    private function isConnectionAlive(?mysqli $connection): bool {
        if ($connection === null) {
            return false;
        }
        
        try {
            // ตรวจสอบว่า connection ยังเปิดอยู่และไม่มี error
            return !$connection->connect_errno && $connection->ping();
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * รับการเชื่อมต่อไปยังฐานข้อมูล employees
     */
    public function getEmployeeConnection(): ?mysqli {
        if (!$this->isConnectionAlive($this->employeeConnection)) {
            $this->employeeConnection = $this->createConnection($this->getConfig('employee'));
        }
        return $this->employeeConnection;
    }

    /**
     * รับการเชื่อมต่อไปยังฐานข้อมูล checklist
     */
    public function getChecklistConnection(): ?mysqli {
        if (!$this->isConnectionAlive($this->checklistConnection)) {
            $this->checklistConnection = $this->createConnection($this->getConfig('checklist'));
        }
        return $this->checklistConnection;
    }

    /**
     * สร้างการเชื่อมต่อใหม่ไปยังฐานข้อมูลที่ระบุ
     */
    private function createConnection(string $database): ?mysqli {
        try {
            $connection = new mysqli(
                $this->getConfig('host'),
                $this->getConfig('user'),
                $this->getConfig('pass'),
                $database
            );

            if ($connection->connect_error) {
                error_log("Database connection failed for {$database}: " . $connection->connect_error);
                return null;
            }

            // ตั้งค่า charset
            if (!$connection->set_charset(self::DB_CHARSET)) {
                error_log("Error setting charset for {$database}: " . $connection->error);
                return null;
            }

            return $connection;

        } catch (Exception $e) {
            error_log("Database connection exception for {$database}: " . $e->getMessage());
            return null;
        }
    }

    /**
     * ตรวจสอบว่าชื่อสถานที่ถูกต้องหรือไม่
     */
    public function isValidLocation(string $location): bool {
        return in_array($location, self::VALID_LOCATIONS, true);
    }

    /**
     * รับรายชื่อสถานที่ทั้งหมดที่ถูกต้อง
     */
    public function getValidLocations(): array {
        return self::VALID_LOCATIONS;
    }

    /**
     * เตรียม prepared statement สำหรับฐานข้อมูล employee
     */
    public function prepareEmployee(string $query): ?mysqli_stmt {
        $conn = $this->getEmployeeConnection();
        if (!$conn) return null;
        
        return $conn->prepare($query);
    }

    /**
     * เตรียม prepared statement สำหรับฐานข้อมูล checklist
     */
    public function prepareChecklist(string $query): ?mysqli_stmt {
        $conn = $this->getChecklistConnection();
        if (!$conn) return null;
        
        return $conn->prepare($query);
    }

    /**
     * รับข้อมูลสินค้าทั้งหมดจากสถานที่ที่ระบุ
     */
    public function getProductsByLocation(string $location): array {
        if (!$this->isValidLocation($location)) {
            return [];
        }

        $conn = $this->getChecklistConnection();
        if (!$conn) return [];

        $sql = "SELECT `id`, `product_code`, `product_name`, `category`, `image_path`, `status`, `note`, `updated_at`, `updated_by` 
                FROM `{$location}` 
                ORDER BY `category`, `product_code`";

        $result = $conn->query($sql);
        if (!$result) {
            error_log("Error getting products for {$location}: " . $conn->error);
            return [];
        }

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * อัปเดตสถานะสินค้า
     */
    public function updateProductStatus(string $location, string $productCode, string $status, string $note = '', string $updatedBy = ''): bool {
        if (!$this->isValidLocation($location)) {
            return false;
        }

        $conn = $this->getChecklistConnection();
        if (!$conn) return false;

        $sql = "UPDATE `{$location}` 
                SET `status` = ?, `note` = ?, `updated_by` = ?, `updated_at` = CURRENT_TIMESTAMP 
                WHERE `product_code` = ?";

        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            error_log("Error preparing update statement for {$location}: " . $conn->error);
            return false;
        }

        $stmt->bind_param('ssss', $status, $note, $updatedBy, $productCode);
        $result = $stmt->execute();
        
        if (!$result) {
            error_log("Error updating product {$productCode} in {$location}: " . $stmt->error);
        }

        $stmt->close();
        return $result;
    }

    /**
     * ปิดการเชื่อมต่อทั้งหมด
     */
    public function closeConnections(): void {
        if ($this->isConnectionAlive($this->employeeConnection)) {
            try {
                $this->employeeConnection->close();
            } catch (Exception $e) {
                // ไม่ต้องทำอะไร connection อาจถูกปิดไปแล้ว
                error_log("Error closing employee connection: " . $e->getMessage());
            }
        }
        $this->employeeConnection = null;
        
        if ($this->isConnectionAlive($this->checklistConnection)) {
            try {
                $this->checklistConnection->close();
            } catch (Exception $e) {
                // ไม่ต้องทำอะไร connection อาจถูกปิดไปแล้ว
                error_log("Error closing checklist connection: " . $e->getMessage());
            }
        }
        $this->checklistConnection = null;
    }

    /**
     * ป้องกันการ clone
     */
    private function __clone() {}

    /**
     * ป้องกันการ unserialize
     */
    public function __wakeup() {
        throw new Exception("Cannot unserialize singleton");
    }

    /**
     * ปิดการเชื่อมต่อเมื่อ object ถูกทำลาย
     */
    public function __destruct() {
        try {
            $this->closeConnections();
        } catch (Exception $e) {
            // ไม่ต้องทำอะไรใน destructor เพื่อป้องกัน fatal error
            error_log("Error in Database destructor: " . $e->getMessage());
        }
    }
}