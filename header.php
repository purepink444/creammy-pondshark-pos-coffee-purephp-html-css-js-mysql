<!-- header.php -->

<link rel="stylesheet" href="style.css">

<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in and has a role
if (isset($_SESSION['role'])) {
    if ($_SESSION['role'] == 'customer') {
        $userName = isset($_SESSION['Name']) ? htmlspecialchars($_SESSION['Name']) : 'ผู้ใช้';
        echo '<nav class="topbar">
            <div class="brand">โสภา คาเฟ่</div>
            <div class="nav-links">
                <a href="index.php" class="active">หน้าแรก</a>
                <a href="menu.php">เมนู</a>
                <a href="profile.php">' . $Username . '</a>
                <a href="logout.php">ออกจากระบบ</a>
            </div>
        </nav>';
    } else if ($_SESSION['role'] == 'employee') {
        echo '<nav class="topbar">
            <div class="brand">โสภา คาเฟ่</div>
            <div class="nav-links">
                <a href="index.php" class="active">หน้าแรก</a>
                <a href="menu.php">เมนู</a>
                <a href="orders.php">คำสั่งซื้อ</a>
                <a href="logout.php">ออกจากระบบ</a>
            </div>
        </nav>';
    }
} else {
    // Guest/not logged in
    echo '<nav class="topbar">
        <div class="brand">โสภา คาเฟ่</div>
        <div class="nav-links">
            <a href="index.php" class="active">หน้าแรก</a>
            <a href="menu.php">เมนู</a>
            <a href="register.php">สมัครสมาชิก</a>
            <a href="login.php">เข้าสู่ระบบ</a>
        </div>
    </nav>';
}
?>