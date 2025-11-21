<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$DB_HOST = getenv('DB_HOST') ?: 'localhost';
$DB_USER = getenv('DB_USER') ?: 'root';
$DB_PASS = getenv('DB_PASSWORD') ?: 'pinkcuteroot';
$DB_NAME = getenv('DB_NAME') ?: 'coffee_pos_system_db';
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

    // 1. CATEGORY
    $conn->exec("CREATE TABLE IF NOT EXISTS CATEGORY(
        CategoryID INT PRIMARY KEY AUTO_INCREMENT,
        CategoryName VARCHAR(40) NOT NULL
    )ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    echo "✓ Table CATEGORY created<br>";

    // 2. MENU
    $conn->exec("CREATE TABLE IF NOT EXISTS MENU(
        MenuID INT PRIMARY KEY AUTO_INCREMENT,
        CategoryID INT,
        MenuName VARCHAR(50) NOT NULL,
        Price DECIMAL(6,2) NOT NULL,
        Description VARCHAR(100),
        Status INT DEFAULT 1,
        FOREIGN KEY (CategoryID) REFERENCES CATEGORY(CategoryID)
    )ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    echo "✓ Table MENU created<br>";

    // 3. CUSTOMER
    $conn->exec("CREATE TABLE IF NOT EXISTS CUSTOMER(
        CustomerID INT PRIMARY KEY AUTO_INCREMENT,
        Name VARCHAR(50) NOT NULL,
        Phone VARCHAR(10),
        Points INT DEFAULT 0
    )ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    echo "✓ Table CUSTOMER created<br>";

    // 4. EMPLOYEE
    $conn->exec("CREATE TABLE IF NOT EXISTS EMPLOYEE(
        EmployeeID INT PRIMARY KEY AUTO_INCREMENT,
        Name VARCHAR(50) NOT NULL,
        Position VARCHAR(30),
        Phone VARCHAR(10)
    )ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    echo "✓ Table EMPLOYEE created<br>";

    // 5. ORDER
    $conn->exec("CREATE TABLE IF NOT EXISTS `ORDER`(
        OrderID INT PRIMARY KEY AUTO_INCREMENT,
        OrderDate DATETIME NOT NULL,
        EmployeeID INT,
        CustomerID INT,
        TotalPrice DECIMAL(7,2) NOT NULL,
        FOREIGN KEY (EmployeeID) REFERENCES EMPLOYEE(EmployeeID),
        FOREIGN KEY (CustomerID) REFERENCES CUSTOMER(CustomerID)
    )ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    echo "✓ Table ORDER created<br>";

    // 6. ORDERDETAIL
    $conn->exec("CREATE TABLE IF NOT EXISTS ORDERDETAIL(
        OrderDetailID INT PRIMARY KEY AUTO_INCREMENT,
        OrderID INT,
        MenuID INT,
        Quantity INT NOT NULL,
        Price DECIMAL(6,2) NOT NULL,
        FOREIGN KEY (OrderID) REFERENCES `ORDER`(OrderID),
        FOREIGN KEY (MenuID) REFERENCES MENU(MenuID)
    )ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    echo "✓ Table ORDERDETAIL created<br>";

    // 7. SUPPLIER
    $conn->exec("CREATE TABLE IF NOT EXISTS SUPPLIER(
        SupplierID INT PRIMARY KEY AUTO_INCREMENT,
        SupplierName VARCHAR(50) NOT NULL,
        Phone VARCHAR(10)
    )ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    echo "✓ Table SUPPLIER created<br>";

    // 8. STOCK
    $conn->exec("CREATE TABLE IF NOT EXISTS STOCK(
        StockID INT PRIMARY KEY AUTO_INCREMENT,
        ItemName VARCHAR(50) NOT NULL,
        Quantity DECIMAL(7,2) NOT NULL,
        Unit VARCHAR(10) NOT NULL,
        SupplierID INT,
        FOREIGN KEY (SupplierID) REFERENCES SUPPLIER(SupplierID)
    )ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    echo "✓ Table STOCK created<br><br>";

    echo "<hr><h3>✓✓✓ All tables created successfully! ✓✓✓</h3><hr>";

} catch (PDOException $e) {
    echo "<br>❌ Error: " . $e->getMessage() . "<br>";
}
?>
