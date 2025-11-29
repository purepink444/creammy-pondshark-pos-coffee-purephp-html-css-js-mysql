<!-- header.php -->

<link rel="stylesheet" href="style.css">

<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if customer is logged in
if (isset($_SESSION['CustomerID']) && isset($_SESSION['Name'])) {
    $userName = htmlspecialchars($_SESSION['Name']);
    echo '<nav class="topbar">
        <div class="brand">โสภา คาเฟ่</div>
        <div class="nav-links">
            <a href="index.php">หน้าแรก</a>
            <a href="menu.php">เมนู</a>
            <a href="profile.php">' . $userName . '</a>
            <a href="logout.php">ออกจากระบบ</a>
        </div>
    </nav>';
} 
// Check if employee is logged in
else if (isset($_SESSION['EmployeeID']) && isset($_SESSION['Name'])) {
    $userName = htmlspecialchars($_SESSION['Name']);
    echo '<nav class="topbar">
        <div class="brand">โสภา คาเฟ่</div>
        <div class="nav-links">
            <a href="index.php">หน้าแรก</a>
            <a href="menu.php">เมนู</a>
            <a href="orders.php">คำสั่งซื้อ</a>
            <a href="addmenu.php">เพิ่มเมนู</a>
            <a href="profile.php">' . $userName . '</a>
            <a href="logout.php">ออกจากระบบ</a>
        </div>
    </nav>';
} 
// Guest/not logged in
else {
    echo '<nav class="topbar">
        <div class="brand">โสภา คาเฟ่</div>
        <div class="nav-links">
            <a href="index.php">หน้าแรก</a>
            <a href="menu.php">เมนู</a>
            <a href="register.php">สมัครสมาชิก</a>
            <a href="login.php">เข้าสู่ระบบ</a>
        </div>
    </nav>';
}
?>