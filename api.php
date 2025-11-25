<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$DB_HOST = getenv('DB_HOST') ?: 'localhost';
$DB_USER = getenv('DB_USER') ?: 'root';
$DB_PASS = getenv('DB_PASSWORD') ?: 'pinkcuteroot';
$DB_NAME = getenv('DB_NAME') ?: 'sophacafe_db';
$DB_PORT = getenv('DB_PORT') ?: '3306';

try {
    $dsn = "mysql:host=$DB_HOST;port=$DB_PORT;charset=utf8mb4";
    $conn = new PDO($dsn, $DB_USER, $DB_PASS);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "✓ Database Connected!<br><br>";

    // Create Database
    $conn->exec("CREATE DATABASE IF NOT EXISTS $DB_NAME CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    echo "✓ Database '$DB_NAME' created successfully<br>";

    $conn->exec("USE $DB_NAME");
    echo "✓ Using database '$DB_NAME'<br><br>";

    // 1. ROLES - ตารางบทบาท (Admin, Cashier, Manager)
    $conn->exec("CREATE TABLE IF NOT EXISTS ROLES(
        RoleID INT NOT NULL AUTO_INCREMENT PRIMARY KEY COMMENT 'รหัสบทบาท',
        RoleName VARCHAR(30) NOT NULL COMMENT 'ชื่อบทบาท (Admin, Cashier, Manager)',
        Description VARCHAR(100) COMMENT 'คำอธิบายบทบาท'
    )ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    echo "✓ Table ROLES created (ตารางบทบาท)<br>";

    // 2. REGISTERS - ตารางผู้ใช้ระบบ (พนักงาน/เจ้าของ) + เพิ่มฟิลด์ Name
    $conn->exec("CREATE TABLE IF NOT EXISTS REGISTERS(
        RegisterID INT NOT NULL AUTO_INCREMENT PRIMARY KEY COMMENT 'รหัสผู้ใช้',
        Username VARCHAR(50) NOT NULL UNIQUE COMMENT 'ชื่อผู้ใช้ (ไม่ซ้ำกัน)',
        Password VARCHAR(255) NOT NULL COMMENT 'รหัสผ่าน (เข้ารหัส)',
        Name VARCHAR(100) COMMENT 'ชื่อ-นามสกุล',
        Email VARCHAR(100) COMMENT 'อีเมล',
        RoleID INT NOT NULL COMMENT 'รหัสบทบาท (FK → roles)',
        Status INT DEFAULT 1 COMMENT 'สถานะ (1=ใช้งาน, 0=ปิดใช้งาน)',
        CreatedAt DATETIME DEFAULT CURRENT_TIMESTAMP COMMENT 'วันที่สร้างบัญชี',
        UpdatedAt DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'วันที่อัปเดตล่าสุด',
        FOREIGN KEY (RoleID) REFERENCES ROLES(RoleID)
    )ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    echo "✓ Table REGISTERS created (ตารางผู้ใช้) + Name Field<br>";

    // 3. CATEGORY - ตารางหมวดหมู่สินค้า (กาแฟ ชา ของหวาน)
    $conn->exec("CREATE TABLE IF NOT EXISTS CATEGORY(
        CategoryID INT NOT NULL AUTO_INCREMENT PRIMARY KEY COMMENT 'รหัสหมวดหมู่',
        CategoryName VARCHAR(40) NOT NULL COMMENT 'ชื่อหมวดหมู่'
    )ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    echo "✓ Table CATEGORY created (ตารางหมวดหมู่)<br>";

    // 4. MENU - ตารางเมนูสินค้า
    $conn->exec("CREATE TABLE IF NOT EXISTS MENU(
        MenuID INT NOT NULL AUTO_INCREMENT PRIMARY KEY COMMENT 'รหัสเมนู',
        CategoryID INT NOT NULL COMMENT 'รหัสหมวดหมู่ (FK → category)',
        MenuName VARCHAR(50) NOT NULL COMMENT 'ชื่อสินค้า',
        Price DECIMAL(6,2) NOT NULL COMMENT 'ราคาขาย',
        Description VARCHAR(100) COMMENT 'รายละเอียดสินค้า',
        Image LONGBLOB COMMENT 'รูปภาพสินค้า (BLOB)',
        ImagePath VARCHAR(255) COMMENT 'เส้นทางรูปภาพ (หากเก็บไฟล์)',
        Status INT DEFAULT 1 COMMENT 'สถานะ (1=มีขาย, 0=หยุดขาย)',
        CreatedAt DATETIME DEFAULT CURRENT_TIMESTAMP COMMENT 'วันที่เพิ่มสินค้า',
        UpdatedAt DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'วันที่อัปเดตล่าสุด',
        FOREIGN KEY (CategoryID) REFERENCES CATEGORY(CategoryID)
    )ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    echo "✓ Table MENU created (ตารางเมนู) + Image Field<br>";

    // 5. CUSTOMER - ตารางลูกค้า
    $conn->exec("CREATE TABLE IF NOT EXISTS CUSTOMER(
        CustomerID INT NOT NULL AUTO_INCREMENT PRIMARY KEY COMMENT 'รหัสลูกค้า',
        Name VARCHAR(50) NOT NULL COMMENT 'ชื่อลูกค้า',
        Phone VARCHAR(10) COMMENT 'เบอร์โทรศัพท์',
        Points INT DEFAULT 0 COMMENT 'คะแนนสะสม (loyalty points)',
        CreatedAt DATETIME DEFAULT CURRENT_TIMESTAMP COMMENT 'วันที่สมัครสมาชิก'
    )ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    echo "✓ Table CUSTOMER created (ตารางลูกค้า)<br>";

    // 6. ORDERS - ตารางใบสั่งซื้อ
    $conn->exec("CREATE TABLE IF NOT EXISTS ORDERS(
        OrderID INT NOT NULL AUTO_INCREMENT PRIMARY KEY COMMENT 'รหัสใบสั่งซื้อ',
        OrderDate DATETIME NOT NULL COMMENT 'วันที่สั่งซื้อ',
        RegisterID INT NOT NULL COMMENT 'รหัสผู้ใช้ (FK → registers)',
        CustomerID INT COMMENT 'รหัสลูกค้า (FK → customer)',
        TotalPrice DECIMAL(7,2) NOT NULL COMMENT 'ราคารวมทั้งสิ้น',
        Discount DECIMAL(6,2) DEFAULT 0 COMMENT 'ส่วนลด',
        PaymentMethod VARCHAR(20) DEFAULT 'Cash' COMMENT 'วิธีชำระเงิน (Cash, Card, QR Code)',
        OrderStatus VARCHAR(20) DEFAULT 'Completed' COMMENT 'สถานะคำสั่ง (Pending, Completed, Cancelled)',
        CreatedAt DATETIME DEFAULT CURRENT_TIMESTAMP COMMENT 'วันที่สร้างใบสั่ง',
        UpdatedAt DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'วันที่อัปเดตล่าสุด',
        FOREIGN KEY (RegisterID) REFERENCES REGISTERS(RegisterID),
        FOREIGN KEY (CustomerID) REFERENCES CUSTOMER(CustomerID)
    )ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    echo "✓ Table ORDERS created (ตารางใบสั่ง)<br>";

    // 7. ORDERDETAIL - ตารางรายละเอียดใบสั่ง
    $conn->exec("CREATE TABLE IF NOT EXISTS ORDERDETAIL(
        OrderDetailID INT NOT NULL AUTO_INCREMENT PRIMARY KEY COMMENT 'รหัสรายละเอียดสั่ง',
        OrderID INT NOT NULL COMMENT 'รหัสใบสั่งซื้อ (FK → orders)',
        MenuID INT NOT NULL COMMENT 'รหัสเมนู (FK → menu)',
        Quantity INT NOT NULL COMMENT 'จำนวนที่สั่ง',
        Price DECIMAL(6,2) NOT NULL COMMENT 'ราคาต่อหน่วย (ราคาจริงตอนขาย)',
        CreatedAt DATETIME DEFAULT CURRENT_TIMESTAMP COMMENT 'วันที่เพิ่มรายการ',
        FOREIGN KEY (OrderID) REFERENCES ORDERS(OrderID),
        FOREIGN KEY (MenuID) REFERENCES MENU(MenuID)
    )ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    echo "✓ Table ORDERDETAIL created (ตารางรายละเอียด)<br>";

    // 8. SUPPLIER - ตารางผู้จัดส่ง
    $conn->exec("CREATE TABLE IF NOT EXISTS SUPPLIER(
        SupplierID INT NOT NULL AUTO_INCREMENT PRIMARY KEY COMMENT 'รหัสผู้จัดส่ง',
        SupplierName VARCHAR(50) NOT NULL COMMENT 'ชื่อผู้จัดส่ง',
        Phone VARCHAR(10) COMMENT 'เบอร์โทรศัพท์ผู้จัดส่ง',
        CreatedAt DATETIME DEFAULT CURRENT_TIMESTAMP COMMENT 'วันที่เพิ่มข้อมูล'
    )ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    echo "✓ Table SUPPLIER created (ตารางผู้จัดส่ง)<br><br>";

    // ========== INSERT SAMPLE DATA ==========
    echo "<hr><h3>📊 Inserting Sample Data</h3><hr>";

    // 1. INSERT ROLES (บทบาท) - ดัชนีเริ่มต้นที่ 601
    $roles = [
        [601, 'Admin', 'ผู้ดูแลระบบ'],
        [602, 'Cashier', 'พนักงานเก็บเงิน'],
        [603, 'Manager', 'ผู้จัดการร้าน']
    ];

    foreach ($roles as $role) {
        $conn->exec("INSERT INTO ROLES (RoleID, RoleName, Description) VALUES ({$role[0]}, '{$role[1]}', '{$role[2]}')");
    }
    echo "✓ Inserted 3 roles (บทบาท) - RoleID: 601-603<br>";

    // 2. INSERT REGISTERS (ผู้ใช้) - ดัชนีเริ่มต้นที่ 101 + เพิ่มชื่อ-นามสกุล
    $registers = [
        [101, 'admin01', password_hash('admin123', PASSWORD_BCRYPT), 'สมชาย ใจดี', 'admin@sophacafe.com', 601],
        [102, 'cashier01', password_hash('cashier123', PASSWORD_BCRYPT), 'วิชญา สวยงาม', 'cashier@sophacafe.com', 602],
        [103, 'manager01', password_hash('manager123', PASSWORD_BCRYPT), 'ประเสริฐ จริงใจ', 'manager@sophacafe.com', 603]
    ];

    foreach ($registers as $reg) {
        $stmt = $conn->prepare("INSERT INTO REGISTERS (RegisterID, Username, Password, Name, Email, RoleID, Status) VALUES (?, ?, ?, ?, ?, ?, 1)");
        $stmt->execute($reg);
    }
    echo "✓ Inserted 3 users (ผู้ใช้) - RegisterID: 101-103 + Name Field<br>";

    // 3. INSERT CATEGORY (หมวดหมู่) - ดัชนีเริ่มต้นที่ 401
    $categories = [
        [401, 'กาแฟ'],
        [402, 'ชา'],
        [403, 'เครื่องดื่มอื่น'],
        [404, 'ของหวาน']
    ];

    foreach ($categories as $cat) {
        $conn->exec("INSERT INTO CATEGORY (CategoryID, CategoryName) VALUES ({$cat[0]}, '{$cat[1]}')");
    }
    echo "✓ Inserted 4 categories (หมวดหมู่) - CategoryID: 401-404<br>";

    // 4. INSERT MENU (เมนู) - ดัชนีเริ่มต้นที่ 301
    $menus = [
        [301, 401, 'Espresso', 40.00, 'กาแฟเอสเพรสโซ่เข้มข้น'],
        [302, 401, 'Americano', 50.00, 'เอสเพรสโซ+น้ำร้อน'],
        [303, 401, 'Cappuccino', 60.00, 'เอสเพรสโซ+นมร้อน+ฟอง'],
        [304, 401, 'Latte', 65.00, 'เอสเพรสโซ+นมร้อนมาก'],
        [305, 401, 'Iced Americano', 55.00, 'เอสเพรสโซ+น้ำแข็ง'],
        [306, 401, 'Iced Latte', 70.00, 'เอสเพรสโซ+นมเย็น'],
        [307, 401, 'Cold Brew', 60.00, 'กาแฟชงเย็น'],
        [308, 402, 'Thai Tea', 45.00, 'ชาไทยดั้งเดิม'],
        [309, 402, 'Green Tea', 40.00, 'ชาเขียวญี่ปุ่น'],
        [310, 403, 'Orange Juice', 45.00, 'น้ำส้มสด'],
        [311, 403, 'Lemonade', 40.00, 'น้ำมะนาว'],
        [312, 404, 'Chocolate Cake', 85.00, 'เค้กช็อกโกแลต'],
        [313, 404, 'Croissant', 50.00, 'ครัวซอง'],
        [314, 404, 'Cheesecake', 80.00, 'ชีสเค้กเนื้อนุ่ม']
    ];

    foreach ($menus as $menu) {
        $stmt = $conn->prepare("INSERT INTO MENU (MenuID, CategoryID, MenuName, Price, Description, Status) VALUES (?, ?, ?, ?, ?, 1)");
        $stmt->execute($menu);
    }
    echo "✓ Inserted 14 menu items (เมนู) - MenuID: 301-314<br>";

    // 5. INSERT CUSTOMER (ลูกค้า) - ดัชนีเริ่มต้นที่ 201
    $customers = [
        [201, 'สมชาย ใจดี', '0812345678', 100],
        [202, 'วิชญา สวยใจ', '0823456789', 250],
        [203, 'นายมิ่ง เก่งเรียน', '0834567890', 500],
        [204, 'น้องน้ำ มะวัง', '0845678901', 150],
        [205, 'คุณหนูหวาน รักกาแฟ', '0856789012', 750]
    ];

    foreach ($customers as $cust) {
        $stmt = $conn->prepare("INSERT INTO CUSTOMER (CustomerID, Name, Phone, Points) VALUES (?, ?, ?, ?)");
        $stmt->execute($cust);
    }
    echo "✓ Inserted 5 customers (ลูกค้า) - CustomerID: 201-205<br>";

    // 6. INSERT ORDERS (ใบสั่ง) - ดัชนีเริ่มต้นที่ 701
    $orders = [
        [701, '2025-11-22 08:30:00', 101, 201, 155.00, 0, 'Cash', 'Completed'],
        [702, '2025-11-22 09:15:00', 102, 202, 165.00, 10, 'Card', 'Completed'],
        [703, '2025-11-22 10:00:00', 101, NULL, 215.00, 0, 'Cash', 'Completed'],
        [704, '2025-11-22 11:30:00', 103, 203, 125.00, 5, 'QR Code', 'Completed'],
        [705, '2025-11-22 14:45:00', 102, 204, 185.00, 0, 'Card', 'Completed']
    ];

    foreach ($orders as $order) {
        $stmt = $conn->prepare("INSERT INTO ORDERS (OrderID, OrderDate, RegisterID, CustomerID, TotalPrice, Discount, PaymentMethod, OrderStatus) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute($order);
    }
    echo "✓ Inserted 5 orders (ใบสั่ง) - OrderID: 701-705<br>";

    // 7. INSERT ORDERDETAIL (รายละเอียดสั่ง) - ดัชนีเริ่มต้นที่ 801
    $orderdetails = [
        [801, 701, 301, 2, 40.00],
        [802, 701, 303, 1, 60.00],
        [803, 701, 312, 1, 85.00],
        [804, 702, 302, 1, 50.00],
        [805, 702, 304, 2, 65.00],
        [806, 703, 305, 2, 55.00],
        [807, 703, 306, 1, 70.00],
        [808, 703, 314, 1, 80.00],
        [809, 704, 308, 1, 45.00],
        [810, 704, 313, 1, 50.00],
        [811, 705, 307, 2, 60.00],
        [812, 705, 310, 1, 45.00],
        [813, 705, 311, 1, 40.00]
    ];

    foreach ($orderdetails as $detail) {
        $stmt = $conn->prepare("INSERT INTO ORDERDETAIL (OrderDetailID, OrderID, MenuID, Quantity, Price) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute($detail);
    }
    echo "✓ Inserted 13 order details (รายละเอียด) - OrderDetailID: 801-813<br>";

    // 8. INSERT SUPPLIER (ผู้จัดส่ง) - ดัชนีเริ่มต้นที่ 501
    $suppliers = [
        [501, 'กาแฟสยามพรีเมียม', '0612345678'],
        [502, 'นมบริสุทธิ์โคนม', '0623456789'],
        [503, 'เครื่องดื่มเพชรพลัส', '0634567890'],
        [504, 'ของหวานอร่อยใจ', '0645678901'],
        [505, 'น้ำผลไม้เชื่อมหวาน', '0656789012']
    ];

    foreach ($suppliers as $sup) {
        $stmt = $conn->prepare("INSERT INTO SUPPLIER (SupplierID, SupplierName, Phone) VALUES (?, ?, ?)");
        $stmt->execute($sup);
    }
    echo "✓ Inserted 5 suppliers (ผู้จัดส่ง) - SupplierID: 501-505<br><br>";

    echo "<hr>";
    echo "<h3>✓✓✓ Database Created & Sample Data Inserted Successfully! ✓✓✓</h3>";
    echo "<hr>";

} catch (PDOException $e) {
    echo "<br>❌ Error: " . $e->getMessage() . "<br>";
}
?>