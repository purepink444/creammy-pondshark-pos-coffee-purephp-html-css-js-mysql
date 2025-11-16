-- สร้างฐานข้อมูล
CREATE DATABASE coffee_pos_system_db
    WITH 
    ENCODING = 'UTF8'
    LC_COLLATE = 'en_US.UTF-8'
    LC_CTYPE = 'en_US.UTF-8'
    TEMPLATE = template0;

-- เชื่อมต่อกับฐานข้อมูล
\c coffee_pos_system_db;

-- สร้างตาราง Roles
CREATE TABLE IF NOT EXISTS Roles (
    RoleID SERIAL PRIMARY KEY,
    RoleName VARCHAR(50) NOT NULL
);

-- สร้างตาราง Users
CREATE TABLE IF NOT EXISTS Users (
    UserID SERIAL PRIMARY KEY,
    Username VARCHAR(50) NOT NULL UNIQUE,
    Firstname VARCHAR(100) NOT NULL,
    Lastname VARCHAR(100) NOT NULL,
    Email VARCHAR(100) NOT NULL UNIQUE,
    Password VARCHAR(255) NOT NULL,
    RoleID INTEGER,
    CONSTRAINT fk_user_role FOREIGN KEY (RoleID) 
        REFERENCES Roles(RoleID)
        ON DELETE SET NULL
        ON UPDATE CASCADE
);

-- สร้าง Index สำหรับ Users
CREATE INDEX idx_users_username ON Users(Username);
CREATE INDEX idx_users_email ON Users(Email);
CREATE INDEX idx_users_roleid ON Users(RoleID);

-- สร้างตาราง Menus
CREATE TABLE IF NOT EXISTS Menus (
    MenuID SERIAL PRIMARY KEY,
    Menuname VARCHAR(100) NOT NULL,
    Price DECIMAL(10, 2) NOT NULL CHECK (Price >= 0),
    Description TEXT,
    Path VARCHAR(255)
);

-- สร้าง Index สำหรับ Menus
CREATE INDEX idx_menus_menuname ON Menus(Menuname);

-- สร้างตาราง ProductTypes
CREATE TABLE IF NOT EXISTS ProductTypes (
    ProductTypeID SERIAL PRIMARY KEY,
    ProductType VARCHAR(100) NOT NULL,
    Favorite BOOLEAN DEFAULT FALSE,
    MenuID INTEGER,
    CONSTRAINT fk_producttype_menu FOREIGN KEY (MenuID) 
        REFERENCES Menus(MenuID)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);

-- สร้าง Index สำหรับ ProductTypes
CREATE INDEX idx_producttypes_menuid ON ProductTypes(MenuID);

-- สร้างตาราง Orders
CREATE TABLE IF NOT EXISTS Orders (
    OrderID SERIAL PRIMARY KEY,
    OrderDate TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    Total DECIMAL(10, 2) CHECK (Total >= 0),
    Amount DECIMAL(10, 2) CHECK (Amount >= 0),
    UserID INTEGER,
    CONSTRAINT fk_order_user FOREIGN KEY (UserID) 
        REFERENCES Users(UserID)
        ON DELETE SET NULL
        ON UPDATE CASCADE
);

-- สร้าง Index สำหรับ Orders
CREATE INDEX idx_orders_userid ON Orders(UserID);
CREATE INDEX idx_orders_orderdate ON Orders(OrderDate);

-- สร้างตาราง Orderdetails
CREATE TABLE IF NOT EXISTS Orderdetails (
    OrderDetailsID SERIAL PRIMARY KEY,
    OrderID INTEGER,
    MenuID INTEGER,
    Quantity INTEGER NOT NULL CHECK (Quantity > 0),
    Price DECIMAL(10, 2) NOT NULL CHECK (Price >= 0),
    CONSTRAINT fk_orderdetail_order FOREIGN KEY (OrderID) 
        REFERENCES Orders(OrderID)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    CONSTRAINT fk_orderdetail_menu FOREIGN KEY (MenuID) 
        REFERENCES Menus(MenuID)
        ON DELETE RESTRICT
        ON UPDATE CASCADE
);

-- สร้าง Index สำหรับ Orderdetails
CREATE INDEX idx_orderdetails_orderid ON Orderdetails(OrderID);
CREATE INDEX idx_orderdetails_menuid ON Orderdetails(MenuID);

-- สร้างตาราง Payments
CREATE TABLE IF NOT EXISTS Payments (
    PaymentID SERIAL PRIMARY KEY,
    PaymentType VARCHAR(50) NOT NULL,
    Amount DECIMAL(10, 2) NOT NULL CHECK (Amount >= 0),
    PaymentDate TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    Paid BOOLEAN DEFAULT FALSE,
    Status VARCHAR(50),
    OrderID INTEGER,
    UserID INTEGER,
    CONSTRAINT fk_payment_order FOREIGN KEY (OrderID) 
        REFERENCES Orders(OrderID)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    CONSTRAINT fk_payment_user FOREIGN KEY (UserID) 
        REFERENCES Users(UserID)
        ON DELETE SET NULL
        ON UPDATE CASCADE
);

-- สร้าง Index สำหรับ Payments
CREATE INDEX idx_payments_orderid ON Payments(OrderID);
CREATE INDEX idx_payments_userid ON Payments(UserID);
CREATE INDEX idx_payments_paymentdate ON Payments(PaymentDate);