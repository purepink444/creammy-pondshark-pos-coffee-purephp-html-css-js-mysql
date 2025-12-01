<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', '/pos-php-pdo/error.log');
trigger_error("This is a test error message.", E_USER_WARNING);


// Database Configuration
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

    // ========== CREATE TABLES ==========
    echo "<h3>📋 Creating 9 Tables (ครบทุกความสัมพันธ์)</h3>";

    // 1. ROLES - ตารางบทบาทผู้ใช้
    $conn->exec("CREATE TABLE IF NOT EXISTS ROLES(
        RoleID INT NOT NULL AUTO_INCREMENT PRIMARY KEY COMMENT 'รหัสบทบาท',
        RoleName VARCHAR(30) NOT NULL UNIQUE COMMENT 'ชื่อบทบาท',
        Description VARCHAR(100) COMMENT 'คำอธิบายบทบาท',
        CreatedAt DATETIME DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    echo "✓ Table 1/9: ROLES created<br>";

    // 2. EMPLOYEE - ตารางพนักงาน 
    $conn->exec("CREATE TABLE IF NOT EXISTS EMPLOYEE(
        EmployeeID INT NOT NULL AUTO_INCREMENT PRIMARY KEY COMMENT 'รหัสพนักงาน',
        Username VARCHAR(50) NOT NULL UNIQUE COMMENT 'ชื่อผู้ใช้',
        Password VARCHAR(255) NOT NULL COMMENT 'รหัสผ่าน',
        Prefix ENUM('นาย', 'นางสาว', 'นาง') COMMENT 'คำนำหน้า',
        Name VARCHAR(100) NOT NULL COMMENT 'ชื่อ-นามสกุล',
        Email VARCHAR(100) UNIQUE COMMENT 'อีเมล',
        Phone VARCHAR(10) COMMENT 'เบอร์โทรศัพท์',
        RoleID INT NOT NULL COMMENT 'รหัสบทบาท',
        Status ENUM('Active', 'Inactive') DEFAULT 'Active' COMMENT 'สถานะ',
        CreatedAt DATETIME DEFAULT CURRENT_TIMESTAMP,
        UpdatedAt DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (RoleID) REFERENCES ROLES(RoleID) ON DELETE RESTRICT
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    echo "✓ Table 2/9: EMPLOYEE created (เชื่อม → ROLES)<br>";

    // 3. SUPPLIER - ตารางผู้จัดจำหน่ายวัตถุดิบ (ต้องสร้างก่อน MENU)
    $conn->exec("CREATE TABLE IF NOT EXISTS SUPPLIER(
        SupplierID INT NOT NULL AUTO_INCREMENT PRIMARY KEY COMMENT 'รหัสผู้จัดจำหน่ายวัตถุดิบ',
        SupplierName VARCHAR(100) NOT NULL COMMENT 'ชื่อผู้จัดจำหน่ายวัตถุดิบ',
        ContactPerson VARCHAR(100) COMMENT 'ชื่อผู้ติดต่อ',
        Phone VARCHAR(10) COMMENT 'เบอร์โทร',
        Email VARCHAR(100) COMMENT 'อีเมล',
        Address TEXT COMMENT 'ที่อยู่',
        Status ENUM('Active', 'Inactive') DEFAULT 'Active',
        CreatedAt DATETIME DEFAULT CURRENT_TIMESTAMP,
        UpdatedAt DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    echo "✓ Table 3/9: SUPPLIER created<br>";

    // 4. CATEGORY - ตารางหมวดหมู่สินค้า
    $conn->exec("CREATE TABLE IF NOT EXISTS CATEGORY(
        CategoryID INT NOT NULL AUTO_INCREMENT PRIMARY KEY COMMENT 'รหัสหมวดหมู่สินค้า',
        CategoryName VARCHAR(40) NOT NULL UNIQUE COMMENT 'ชื่อหมวดหมู่สินค้า',
        Description VARCHAR(100) COMMENT 'คำอธิบาย',
        Status ENUM('Active', 'Inactive') DEFAULT 'Active',
        CreatedAt DATETIME DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    echo "✓ Table 4/9: CATEGORY created<br>";

    // 5. MENU - ตารางเมนู (เพิ่ม SupplierID เชื่อมกับ SUPPLIER)
    $conn->exec("CREATE TABLE IF NOT EXISTS MENU(
        MenuID INT NOT NULL AUTO_INCREMENT PRIMARY KEY COMMENT 'รหัสเมนู',
        CategoryID INT NOT NULL COMMENT 'รหัสหมวดหมู่',
        SupplierID INT NULL COMMENT 'รหัสผู้จัดจำหน่ายหลัก',
        MenuName VARCHAR(50) NOT NULL COMMENT 'ชื่อสินค้า',
        Price DECIMAL(8,2) NOT NULL COMMENT 'ราคาขาย',
        Cost DECIMAL(8,2) DEFAULT 0 COMMENT 'ต้นทุน',
        Description TEXT COMMENT 'รายละเอียด',
        ImagePath VARCHAR(255) COMMENT 'เส้นทางรูปภาพ',
        Status ENUM('Available', 'Unavailable') DEFAULT 'Available',
        IsPopular BOOLEAN DEFAULT FALSE COMMENT 'เมนูยอดนิยม',
        CreatedAt DATETIME DEFAULT CURRENT_TIMESTAMP,
        UpdatedAt DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (CategoryID) REFERENCES CATEGORY(CategoryID) ON DELETE RESTRICT,
        FOREIGN KEY (SupplierID) REFERENCES SUPPLIER(SupplierID) ON DELETE SET NULL,
        INDEX idx_category (CategoryID),
        INDEX idx_supplier (SupplierID),
        INDEX idx_status (Status)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    echo "✓ Table 5/9: MENU created (เชื่อม → CATEGORY, SUPPLIER)<br>";

    // 6. CUSTOMER - ตารางลูกค้า
    $conn->exec("CREATE TABLE IF NOT EXISTS CUSTOMER(
        CustomerID INT NOT NULL AUTO_INCREMENT PRIMARY KEY COMMENT 'รหัสลูกค้า',
        Username VARCHAR(100) NOT NULL COMMENT 'ชื่อผู้ใช้',
        Prefix ENUM('นาย', 'นางสาว', 'นาง') COMMENT 'คำนำหน้า',
        Name VARCHAR(100) NOT NULL COMMENT 'ชื่อลูกค้า',
        Phone VARCHAR(10) UNIQUE COMMENT 'เบอร์โทร',
        Email VARCHAR(100) UNIQUE COMMENT 'อีเมล',
        Password VARCHAR(255) COMMENT 'รหัสผ่าน',
        Points INT DEFAULT 0 COMMENT 'คะแนนสะสม',
        MemberLevel ENUM('Bronze', 'Silver', 'Gold', 'Platinum') DEFAULT 'Bronze',
        Status ENUM('Active', 'Inactive') DEFAULT 'Active',
        CreatedAt DATETIME DEFAULT CURRENT_TIMESTAMP,
        UpdatedAt DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        INDEX idx_phone (Phone)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    echo "✓ Table 6/9: CUSTOMER created<br>";

    // 7. PROMOTION - ตารางโปรโมชั่น (ต้องสร้างก่อน ORDERS)
    $conn->exec("CREATE TABLE IF NOT EXISTS PROMOTION(
        PromotionID INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
        PromotionName VARCHAR(100) NOT NULL,
        Description TEXT,
        DiscountType ENUM('Percent', 'Fixed') NOT NULL COMMENT 'Percent=ลด%, Fixed=ลดเงินตายตัว',
        DiscountValue DECIMAL(8,2) NOT NULL COMMENT 'มูลค่าส่วนลด',
        MinPurchase DECIMAL(8,2) DEFAULT 0 COMMENT 'ยอดซื้อขั้นต่ำ',
        StartDate DATE NOT NULL,
        EndDate DATE NOT NULL,
        Status ENUM('Active', 'Inactive', 'Expired') DEFAULT 'Active',
        CreatedAt DATETIME DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    echo "✓ Table 7/9: PROMOTION created<br>";

    // 8. ORDERS - ตารางใบสั่งซื้อ (เพิ่ม PromotionID เชื่อมกับ PROMOTION)
    $conn->exec("CREATE TABLE IF NOT EXISTS ORDERS(
        OrderID INT NOT NULL AUTO_INCREMENT PRIMARY KEY COMMENT 'รหัสใบสั่ง',
        OrderNumber VARCHAR(20) UNIQUE NOT NULL COMMENT 'เลขที่ใบสั่ง',
        OrderDate DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'วันที่สั่ง',
        EmployeeID INT NOT NULL COMMENT 'รหัสพนักงาน',
        CustomerID INT COMMENT 'รหัสลูกค้า',
        PromotionID INT NULL COMMENT 'รหัสโปรโมชั่นที่ใช้',
        SubTotal DECIMAL(10,2) NOT NULL COMMENT 'ราคารวมก่อนส่วนลด',
        Discount DECIMAL(8,2) DEFAULT 0 COMMENT 'ส่วนลด',
        Tax DECIMAL(8,2) DEFAULT 0 COMMENT 'ภาษี',
        TotalPrice DECIMAL(10,2) NOT NULL COMMENT 'ราคารวมสุทธิ',
        PaymentMethod ENUM('Cash', 'Card', 'QR Code', 'Transfer') DEFAULT 'Cash',
        PaymentStatus ENUM('Pending', 'Paid', 'Refunded') DEFAULT 'Paid',
        OrderStatus ENUM('Pending', 'Preparing', 'Completed', 'Cancelled') DEFAULT 'Completed',
        Note TEXT COMMENT 'หมายเหตุ',
        CreatedAt DATETIME DEFAULT CURRENT_TIMESTAMP,
        UpdatedAt DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (EmployeeID) REFERENCES EMPLOYEE(EmployeeID) ON DELETE RESTRICT,
        FOREIGN KEY (CustomerID) REFERENCES CUSTOMER(CustomerID) ON DELETE SET NULL,
        FOREIGN KEY (PromotionID) REFERENCES PROMOTION(PromotionID) ON DELETE SET NULL,
        INDEX idx_order_date (OrderDate),
        INDEX idx_employee (EmployeeID),
        INDEX idx_customer (CustomerID),
        INDEX idx_promotion (PromotionID)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    echo "✓ Table 8/9: ORDERS created (เชื่อม → EMPLOYEE, CUSTOMER, PROMOTION)<br>";

    // 9. ORDERDETAIL - ตารางรายละเอียดใบสั่งเมนู
    $conn->exec("CREATE TABLE IF NOT EXISTS ORDERDETAIL(
        OrderDetailID INT NOT NULL AUTO_INCREMENT PRIMARY KEY COMMENT 'รหัสรายละเอียด',
        OrderID INT NOT NULL COMMENT 'รหัสใบสั่ง',
        MenuID INT NOT NULL COMMENT 'รหัสเมนู',
        MenuName VARCHAR(50) NOT NULL COMMENT 'ชื่อเมนู (เก็บไว้เผื่อเมนูถูกลบ)',
        Quantity INT NOT NULL DEFAULT 1 COMMENT 'จำนวน',
        Price DECIMAL(8,2) NOT NULL COMMENT 'ราคาต่อหน่วย',
        Subtotal DECIMAL(10,2) NOT NULL COMMENT 'ราคารวม',
        Note VARCHAR(255) COMMENT 'หมายเหตุ (เช่น เพิ่มน้ำตาล)',
        CreatedAt DATETIME DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (OrderID) REFERENCES ORDERS(OrderID) ON DELETE CASCADE,
        FOREIGN KEY (MenuID) REFERENCES MENU(MenuID) ON DELETE RESTRICT,
        INDEX idx_order (OrderID)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    echo "✓ Table 9/9: ORDERDETAIL created (เชื่อม → ORDERS, MENU)<br><br>";

    // ========== INSERT SAMPLE DATA ==========
    echo "<hr><h3>📊 Inserting Sample Data</h3><hr>";

    // 1. ROLES
    $roles = [
        [601, 'Admin', 'ผู้ดูแลระบบ มีสิทธิ์เต็ม'],
        [602, 'Manager', 'ผู้จัดการ จัดการร้าน รายงาน'],
        [603, 'Cashier', 'พนักงานขาย รับออร์เดอร์']
    ];
    $stmt = $conn->prepare("INSERT IGNORE INTO ROLES (RoleID, RoleName, Description) VALUES (?, ?, ?)");
    foreach ($roles as $role) $stmt->execute($role);
    echo "✓ Inserted 3 roles<br>";

    // 2. EMPLOYEE
    $employees = [
        [101, 'admin01', password_hash('admin123', PASSWORD_BCRYPT), 'สมชาย ใจดี', 'admin@sophacafe.com', '0812345678', 601],
        [102, 'manager01', password_hash('manager123', PASSWORD_BCRYPT), 'ประเสริฐ จริงใจ', 'manager@sophacafe.com', '0823456789', 602],
        [103, 'cashier01', password_hash('cashier123', PASSWORD_BCRYPT), 'วิชญา สวยงาม', 'cashier1@sophacafe.com', '0834567890', 603],
        [104, 'cashier02', password_hash('cashier123', PASSWORD_BCRYPT), 'สมหญิง ยิ้มสวย', 'cashier2@sophacafe.com', '0845678901', 603]
    ];
    $stmt = $conn->prepare("INSERT IGNORE INTO EMPLOYEE (EmployeeID, Username, Password, Name, Email, Phone, RoleID) VALUES (?, ?, ?, ?, ?, ?, ?)");
    foreach ($employees as $emp) $stmt->execute($emp);
    echo "✓ Inserted 4 employees<br>";

    // 3. SUPPLIER
    $suppliers = [
        [501, 'กาแฟสยามพรีเมียม', 'คุณสมชาย', '0612345678', 'supplier1@email.com', 'กรุงเทพฯ'],
        [502, 'นมบริสุทธิ์โคนม', 'คุณวิชัย', '0623456789', 'supplier2@email.com', 'นครปฐม'],
        [503, 'เครื่องดื่มเพชรพลัส', 'คุณสมหญิง', '0634567890', 'supplier3@email.com', 'สมุทรสาคร'],
        [504, 'ของหวานอร่อยใจ', 'คุณประเสริฐ', '0645678901', 'supplier4@email.com', 'กรุงเทพฯ']
    ];
    $stmt = $conn->prepare("INSERT IGNORE INTO SUPPLIER (SupplierID, SupplierName, ContactPerson, Phone, Email, Address) VALUES (?, ?, ?, ?, ?, ?)");
    foreach ($suppliers as $sup) $stmt->execute($sup);
    echo "✓ Inserted 4 suppliers<br>";

    // 4. CATEGORY
    $categories = [
        [401, 'กาแฟ', 'เครื่องดื่มกาแฟทุกชนิด'],
        [402, 'ชา', 'ชาร้อน ชาเย็น'],
        [403, 'เครื่องดื่มอื่นๆ', 'น้ำผลไม้ โซดา'],
        [404, 'ของหวาน', 'เค้ก ขนมปัง คุกกี้'],
        [405, 'อาหารว่าง', 'แซนวิช สลัด']
    ];
    $stmt = $conn->prepare("INSERT IGNORE INTO CATEGORY (CategoryID, CategoryName, Description) VALUES (?, ?, ?)");
    foreach ($categories as $cat) $stmt->execute($cat);
    echo "✓ Inserted 5 categories<br>";

    // 5. MENU (เพิ่ม SupplierID)
    $menus = [
        [301, 401, 501, 'Espresso', 40.00, 20.00, 'กาแฟเอสเพรสโซ่เข้มข้น', 1],
        [302, 401, 501, 'Americano', 50.00, 25.00, 'เอสเพรสโซ + น้ำร้อน', 1],
        [303, 401, 501, 'Cappuccino', 60.00, 30.00, 'เอสเพรสโซ + นมร้อน + ฟองนม', 1],
        [304, 401, 501, 'Latte', 65.00, 32.00, 'เอสเพรสโซ + นมร้อน', 1],
        [305, 401, 501, 'Mocha', 70.00, 35.00, 'ลาเต้ + ช็อกโกแลต', 1],
        [306, 401, 501, 'Iced Americano', 55.00, 27.00, 'เอสเพรสโซ + น้ำแข็ง', 1],
        [307, 401, 501, 'Iced Latte', 70.00, 35.00, 'เอสเพรสโซ + นมเย็น', 1],
        [308, 401, 501, 'Cold Brew', 75.00, 38.00, 'กาแฟชงเย็น 12 ชั่วโมง', 1],
        [309, 402, 503, 'Thai Tea', 45.00, 20.00, 'ชาไทยแท้', 1],
        [310, 402, 503, 'Green Tea', 40.00, 18.00, 'ชาเขียวญี่ปุ่น', 1],
        [311, 402, 503, 'Milk Tea', 50.00, 22.00, 'ชานมไต้หวัน', 1],
        [312, 403, 503, 'Orange Juice', 45.00, 25.00, 'น้ำส้มคั้นสด', 1],
        [313, 403, 503, 'Lemonade', 40.00, 20.00, 'น้ำมะนาวสด', 1],
        [314, 403, 503, 'Smoothie', 60.00, 30.00, 'สมูทตี้ผลไม้รวม', 1],
        [315, 404, 504, 'Chocolate Cake', 85.00, 40.00, 'เค้กช็อกโกแลตเข้มข้น', 1],
        [316, 404, 504, 'Cheesecake', 90.00, 45.00, 'ชีสเค้กนิวยอร์ก', 1],
        [317, 404, 504, 'Croissant', 50.00, 20.00, 'ครัวซองต์เนยแท้', 1],
        [318, 404, 504, 'Brownie', 55.00, 25.00, 'บราวนี่ช็อกโกแลต', 1],
        [319, 405, 504, 'Club Sandwich', 95.00, 45.00, 'แซนวิชคลับ 3 ชั้น', 1],
        [320, 405, 504, 'Caesar Salad', 85.00, 40.00, 'สลัดซีซาร์', 1]
    ];
    $stmt = $conn->prepare("INSERT IGNORE INTO MENU (MenuID, CategoryID, SupplierID, MenuName, Price, Cost, Description, IsPopular) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    foreach ($menus as $menu) $stmt->execute($menu);
    echo "✓ Inserted 20 menu items (เชื่อมกับ Supplier)<br>";

    // 6. CUSTOMER
    $customers = [
        [201, 'somchai01', 'สมชาย ใจดี', '0891234567', 'customer1@email.com', password_hash('customer123', PASSWORD_BCRYPT), 150, 'Bronze'],
        [202, 'wichaya01', 'วิชญา สวยใจ', '0892345678', 'customer2@email.com', password_hash('customer123', PASSWORD_BCRYPT), 350, 'Silver'],
        [203, 'ming01', 'นายมิ่ง เก่งเรียน', '0893456789', 'customer3@email.com', password_hash('customer123', PASSWORD_BCRYPT), 580, 'Gold'],
        [204, 'nam01', 'น้องน้ำ มะวัง', '0894567890', 'customer4@email.com', password_hash('customer123', PASSWORD_BCRYPT), 220, 'Bronze'],
        [205, 'noo01', 'คุณหนู รักกาแฟ', '0895678901', 'customer5@email.com', password_hash('customer123', PASSWORD_BCRYPT), 850, 'Platinum']
    ];
    $stmt = $conn->prepare("INSERT IGNORE INTO CUSTOMER (CustomerID, Username, Name, Phone, Email, Password, Points, MemberLevel) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    foreach ($customers as $cust) $stmt->execute($cust);
    echo "✓ Inserted 5 customers<br>";

    // 7. PROMOTION
    $promotions = [
        [1001, 'ลด 10% ทุกเมนู', 'ส่วนลด 10% สำหรับทุกเมนู ไม่มีขั้นต่ำ', 'Percent', 10.00, 0, '2024-01-01', '2024-12-31', 'Active'],
        [1002, 'ซื้อครบ 200 ลด 50', 'ซื้อครบ 200 บาท ลดทันที 50 บาท', 'Fixed', 50.00, 200.00, '2024-01-01', '2024-12-31', 'Active'],
        [1003, 'Happy Hour 15-17 โมง', 'กาแฟทุกแก้วลด 20 บาท ช่วง 15:00-17:00', 'Fixed', 20.00, 0, '2024-01-01', '2024-12-31', 'Active']
    ];
    $stmt = $conn->prepare("INSERT IGNORE INTO PROMOTION (PromotionID, PromotionName, Description, DiscountType, DiscountValue, MinPurchase, StartDate, EndDate, Status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    foreach ($promotions as $promo) $stmt->execute($promo);
    echo "✓ Inserted 3 promotions<br>";

    // 8. ORDERS (เพิ่ม PromotionID)
    $orderDate = date('Y-m-d H:i:s', strtotime('-1 day'));
    $orders = [
        [701, 'ORD'.date('Ymd').'001', $orderDate, 103, 201, 1001, 200.00, 20.00, 12.60, 192.60, 'Cash'],
        [702, 'ORD'.date('Ymd').'002', $orderDate, 103, 202, 1002, 250.00, 50.00, 14.00, 214.00, 'Card'],
        [703, 'ORD'.date('Ymd').'003', $orderDate, 104, NULL, NULL, 125.00, 0, 8.75, 133.75, 'QR Code']
    ];
    $stmt = $conn->prepare("INSERT IGNORE INTO ORDERS (OrderID, OrderNumber, OrderDate, EmployeeID, CustomerID, PromotionID, SubTotal, Discount, Tax, TotalPrice, PaymentMethod) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    foreach ($orders as $order) $stmt->execute($order);
    echo "✓ Inserted 3 sample orders (เชื่อมกับ Promotion)<br>";

    // 9. ORDERDETAIL
    $details = [
        [1, 701, 304, 'Latte', 2, 65.00, 130.00, 'หวานน้อย'],
        [2, 701, 315, 'Chocolate Cake', 1, 85.00, 85.00, NULL],
        [3, 702, 303, 'Cappuccino', 2, 60.00, 120.00, NULL],
        [4, 702, 309, 'Thai Tea', 2, 45.00, 90.00, 'เย็นมาก'],
        [5, 703, 302, 'Americano', 3, 50.00, 150.00, 'ร้อนพิเศษ']
    ];
    $stmt = $conn->prepare("INSERT IGNORE INTO ORDERDETAIL (OrderDetailID, OrderID, MenuID, MenuName, Quantity, Price, Subtotal, Note) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    foreach ($details as $detail) $stmt->execute($detail);
    echo "✓ Inserted 5 order details<br>";

    echo "<br><hr>";
    echo "<h2 style='color: green;'>✅ สำเร็จ! สร้างครบ 9 ตาราง พร้อมความสัมพันธ์ครบถ้วน</h2>";
    echo "<hr>";
    echo "<h3>🔗 สรุปความสัมพันธ์:</h3>";
    echo "<ul>";
    echo "<li>✅ SUPPLIER → MENU (ซัพพลายเออร์จัดหาวัตถุดิบให้เมนู)</li>";
    echo "<li>✅ PROMOTION → ORDERS (โปรโมชั่นใช้กับออร์เดอร์)</li>";
    echo "<li>✅ ทุกตารางเชื่อมโยงกันครบถ้วน</li>";
    echo "</ul>";
    echo "<hr>";
    echo "<h3>📌 Login Credentials:</h3>";
    echo "<ul>";
    echo "<li><strong>Admin:</strong> admin01 / admin123</li>";
    echo "<li><strong>Manager:</strong> manager01 / manager123</li>";
    echo "<li><strong>Cashier:</strong> cashier01 / cashier123</li>";
    echo "</ul>";

} catch (PDOException $e) {
    echo "<br>❌ Error: " . $e->getMessage() . "<br>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}
?>