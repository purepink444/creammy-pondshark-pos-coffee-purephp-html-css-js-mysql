<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// ใช้ค่าเริ่มต้นถ้าไม่มี environment variables
$DB_HOST = getenv('DB_HOST') ?: 'localhost';
$DB_USER = getenv('DB_USER') ?: 'root';
$DB_PASS = getenv('DB_PASSWORD') ?: 'pinkcuteroot';
$DB_NAME = getenv('DB_NAME') ?: 'coffee_pos_system_db';
$DB_PORT = getenv('DB_PORT') ?: '3306';

try {
    // เชื่อมต่อโดยไม่ระบุ database ก่อน
    $dsn = "mysql:host=$DB_HOST;port=$DB_PORT;charset=utf8mb4";
    $conn = new PDO($dsn, $DB_USER, $DB_PASS);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "✓ Database Connected!<br><br>";
    
    // สร้าง Database
    $conn->exec("CREATE DATABASE IF NOT EXISTS $DB_NAME CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    echo "✓ Database '$DB_NAME' created successfully<br>";
    
    // เลือกใช้ Database
    $conn->exec("USE $DB_NAME");
    echo "✓ Using database '$DB_NAME'<br><br>";
    
    // สร้างตาราง Roles
    $conn->exec("CREATE TABLE IF NOT EXISTS Roles (
        RoleID INT PRIMARY KEY AUTO_INCREMENT,
        RoleName VARCHAR(50) NOT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    echo "✓ Table 'Roles' created<br>";
    
    // สร้างตาราง Users
    $conn->exec("CREATE TABLE IF NOT EXISTS Users (
        UserID INT PRIMARY KEY AUTO_INCREMENT,
        Username VARCHAR(50) NOT NULL UNIQUE,
        Firstname VARCHAR(100) NOT NULL,
        Lastname VARCHAR(100) NOT NULL,
        Email VARCHAR(100) NOT NULL UNIQUE,
        Password VARCHAR(255) NOT NULL,
        RoleID INT,
        FOREIGN KEY (RoleID) REFERENCES Roles(RoleID)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    echo "✓ Table 'Users' created<br>";
    
    // สร้างตาราง Menus
    $conn->exec("CREATE TABLE IF NOT EXISTS Menus (
        MenuID INT PRIMARY KEY AUTO_INCREMENT,
        Menuname VARCHAR(100) NOT NULL,
        Price DECIMAL(10, 2) NOT NULL,
        Description TEXT,
        Path VARCHAR(255)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    echo "✓ Table 'Menus' created<br>";
    
    // สร้างตาราง ProductTypes
    $conn->exec("CREATE TABLE IF NOT EXISTS ProductTypes (
        ProductTypeID INT PRIMARY KEY AUTO_INCREMENT,
        ProductType VARCHAR(100) NOT NULL,
        Favorite BOOLEAN DEFAULT FALSE,
        MenuID INT,
        FOREIGN KEY (MenuID) REFERENCES Menus(MenuID)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    echo "✓ Table 'ProductTypes' created<br>";
    
    // สร้างตาราง Orders
    $conn->exec("CREATE TABLE IF NOT EXISTS Orders (
        OrderID INT PRIMARY KEY AUTO_INCREMENT,
        OrderDate DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        Total DECIMAL(10, 2),
        Amount DECIMAL(10, 2),
        UserID INT,
        FOREIGN KEY (UserID) REFERENCES Users(UserID)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    echo "✓ Table 'Orders' created<br>";
    
    // สร้างตาราง Orderdetails
    $conn->exec("CREATE TABLE IF NOT EXISTS Orderdetails (
        OrderDetailsID INT PRIMARY KEY AUTO_INCREMENT,
        OrderID INT,
        MenuID INT,
        Quantity INT NOT NULL,
        Price DECIMAL(10, 2) NOT NULL,
        FOREIGN KEY (OrderID) REFERENCES Orders(OrderID),
        FOREIGN KEY (MenuID) REFERENCES Menus(MenuID)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    echo "✓ Table 'Orderdetails' created<br>";
    
    // สร้างตาราง Payments
    $conn->exec("CREATE TABLE IF NOT EXISTS Payments (
        PaymentID INT PRIMARY KEY AUTO_INCREMENT,
        PaymentType VARCHAR(50) NOT NULL,
        Amount DECIMAL(10, 2) NOT NULL,
        PaymentDate DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        Paid BOOLEAN DEFAULT FALSE,
        Status VARCHAR(50),
        OrderID INT,
        UserID INT,
        FOREIGN KEY (OrderID) REFERENCES Orders(OrderID),
        FOREIGN KEY (UserID) REFERENCES Users(UserID)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    echo "✓ Table 'Payments' created<br><br>";
    
    
    echo "<br>========================================<br>";
    echo "✓✓✓ All tables created successfully! ✓✓✓<br>";
    echo "========================================<br><br>";
    
    // แสดงข้อมูลในตาราง
    echo "<h3>Database Tables:</h3>";
    $tables = $conn->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
    foreach ($tables as $table) {
        echo "- $table<br>";
    }
    
    echo "<br><h3>Sample Data Preview:</h3>";
    
    echo "<strong>Roles:</strong><br>";
    $stmt = $conn->query("SELECT * FROM Roles");
    while ($row = $stmt->fetch()) {
        echo "- {$row['RoleID']}: {$row['RoleName']}<br>";
    }
    
    echo "<br><strong>Users:</strong><br>";
    $stmt = $conn->query("SELECT Username, Firstname, Lastname, Email FROM Users");
    while ($row = $stmt->fetch()) {
        echo "- {$row['Username']} ({$row['Firstname']} {$row['Lastname']}) - {$row['Email']}<br>";
    }
    
    echo "<br><strong>Menus:</strong><br>";
    $stmt = $conn->query("SELECT Menuname, Price FROM Menus");
    while ($row = $stmt->fetch()) {
        echo "- {$row['Menuname']} - ฿{$row['Price']}<br>";
    }
    
} catch (PDOException $e) {
    echo "<br>❌ Error: " . $e->getMessage() . "<br>";
    echo "Error Code: " . $e->getCode() . "<br>";
}
?>