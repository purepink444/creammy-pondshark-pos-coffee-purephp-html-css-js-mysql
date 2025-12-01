<?php
session_start();

// Check if customer or employee is logged in
if (!isset($_SESSION['CustomerID']) && !isset($_SESSION['EmployeeID'])) {
    header("Location: login.php");
    exit();
}

// Get user data based on session type
$user = null;
if (isset($_SESSION['CustomerID'])) {
    // For now, just use session data since we don't have a getUserById function
    $user = [
        'username' => $_SESSION['Username'] ?? '',
        'fullname' => $_SESSION['Name'] ?? '',
        'phone' => $_SESSION['Phone'] ?? '',
        'email' => $_SESSION['Email'] ?? '',
        'points' => $_SESSION['Points'] ?? 0
    ];
} else {
    // Employee data
    $user = [
        'username' => $_SESSION['Username'] ?? '',
        'fullname' => $_SESSION['Name'] ?? '',
        'phone' => $_SESSION['Phone'] ?? '',
        'email' => $_SESSION['Email'] ?? '',
        'points' => 0
    ];
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>โปรไฟล์ผู้ใช้ - Coffee Shop</title>
    
    <!-- Preload critical resources -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php include('header.php'); ?>

<div class="center-page">
    <div class="card-coffee register-card">
        <h3 class="text-center">โปรไฟล์ผู้ใช้</h3>

        <div class="mt-4">
            <!-- LEFT SECTION -->
            <div class="form-group">
                <label>ชื่อผู้ใช้</label>
                <input type="text" value="<?= htmlspecialchars($user['username'] ?? '') ?>" disabled>
            </div>

            <div class="form-group">
                <label>คะแนนสะสม</label>
                <input type="text" value="<?= number_format($user['points'] ?? 0) ?> คะแนน" disabled>
            </div>
        </div>

        <!-- RIGHT SECTION -->
        <form id="profileForm" method="POST" action="update_profile.php" class="mt-4">
            <div class="form-group">
                <label>ชื่อ-นามสกุล</label>
                <input type="text" name="fullname" value="<?= htmlspecialchars($user['fullname'] ?? '') ?>" required>
            </div>

            <div class="form-group">
                <label>เบอร์โทรศัพท์</label>
                <input type="tel" name="phone" value="<?= htmlspecialchars($user['phone'] ?? '') ?>" pattern="[0-9]{10}" placeholder="0812345678" required>
            </div>

            <div class="form-group">
                <label>อีเมล</label>
                <input type="email" name="email" value="<?= htmlspecialchars($user['email'] ?? '') ?>" required>
            </div>

            <div class="form-group">
                <label>รหัสผ่านใหม่ (เว้นว่างหากไม่ต้องการเปลี่ยน)</label>
                <input type="password" name="new_password" placeholder="กรอกรหัสผ่านใหม่" minlength="6">
            </div>

            <div class="form-group">
                <label>ยืนยันรหัสผ่านใหม่</label>
                <input type="password" name="confirm_password" placeholder="ยืนยันรหัสผ่านใหม่" minlength="6">
            </div>

            <div class="mt-4">
                <button type="button" class="btn-cancel" onclick="window.location.href='menu.php'" style="margin-right: 12px;">ยกเลิก</button>
                <button type="submit" class="btn-coffee">บันทึกข้อมูล</button>
            </div>
        </form>
    </div>
</div>

<script>
// Form Validation
document.getElementById('profileForm').addEventListener('submit', function(e) {
    const newPassword = document.querySelector('input[name="new_password"]').value;
    const confirmPassword = document.querySelector('input[name="confirm_password"]').value;
    
    if (newPassword || confirmPassword) {
        if (newPassword !== confirmPassword) {
            e.preventDefault();
            alert('รหัสผ่านไม่ตรงกัน กรุณาตรวจสอบอีกครั้ง');
            return false;
        }
        
        if (newPassword.length < 6) {
            e.preventDefault();
            alert('รหัสผ่านต้องมีอย่างน้อย 6 ตัวอักษร');
            return false;
        }
    }
});

// Phone number formatting
document.querySelector('input[name="phone"]').addEventListener('input', function(e) {
    this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10);
});
</script>

</body>
</html>