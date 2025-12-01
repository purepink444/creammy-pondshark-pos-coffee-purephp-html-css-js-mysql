<?php
session_start();
require_once 'api.php'; 

// ตรวจสอบ session และ redirect หาก user ไม่ได้ login
if (!isset($_SESSION['user_id'])) {
    header("Location: profile.php");
    exit();
}

// ดึงข้อมูล user ก่อน render HTML
$user_id = $_SESSION['user_id'];
try {
    $user = getUserById($user_id); // สมมติว่ามีฟังก์ชันนี้ใน api.php
    
    if (!$user) {
        // ถ้าไม่พบข้อมูล user ให้ทำลาย session และ redirect
        session_destroy();
        header("Location: profile.php");
        exit();
    }
} catch (Exception $e) {
    error_log("Error loading user profile: " . $e->getMessage());
    die("เกิดข้อผิดพลาดในการโหลดข้อมูล");
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
    
    <link rel="stylesheet" href="style.css?v=<?= time() ?>">
    <link rel="stylesheet" href="profile.css?v=<?= time() ?>">
</head>
<body>

<!-- Navbar -->
<div class="topbar">
    <div class="brand">☕ Coffee Shop</div>
    <div class="nav-links">
        <a href="index.php">หน้าแรก</a>
        <a href="menu.php">เมนู</a>
        <a href="order.php">สั่งซื้อ</a>
        <a href="profile.php">โปรไฟล์</a>
        <a href="logout.php">ออกจากระบบ</a>
    </div>
</div>

<div class="profile-container">
    <div class="profile-card">
        <div class="profile-header">
            <h1>โปรไฟล์ผู้ใช้</h1>
        </div>

        <div class="profile-layout">
            <!-- LEFT SECTION -->
            <div class="profile-left">
                <div class="form-field">
                    <label>ชื่อผู้ใช้</label>
                    <input type="text" 
                           value="<?= htmlspecialchars($user['username'] ?? '') ?>" 
                           disabled>
                </div>

                <div class="score-display">
                    <div class="label">คะแนนสะสม</div>
                    <div class="points"><?= number_format($user['points'] ?? 0) ?></div>
                    <div class="points-text">คะแนน</div>
                </div>
            </div>

            <!-- RIGHT SECTION -->
            <div class="profile-right">
                <form id="profileForm" method="POST" action="update_profile.php">
                    <div class="form-field">
                        <label>ชื่อ-นามสกุล</label>
                        <input type="text" 
                               name="fullname"
                               value="<?= htmlspecialchars($user['fullname'] ?? '') ?>" 
                               required>
                    </div>

                    <div class="form-field">
                        <label>เบอร์โทรศัพท์</label>
                        <input type="tel" 
                               name="phone"
                               value="<?= htmlspecialchars($user['phone'] ?? '') ?>" 
                               pattern="[0-9]{10}"
                               placeholder="0812345678"
                               required>
                    </div>

                    <div class="form-field">
                        <label>อีเมล</label>
                        <input type="email" 
                               name="email"
                               value="<?= htmlspecialchars($user['email'] ?? '') ?>" 
                               required>
                    </div>

                    <div class="form-field">
                        <label>รหัสผ่านใหม่ (เว้นว่างหากไม่ต้องการเปลี่ยน)</label>
                        <input type="password" 
                               name="new_password"
                               placeholder="กรอกรหัสผ่านใหม่"
                               minlength="6">
                    </div>

                    <div class="form-field">
                        <label>ยืนยันรหัสผ่านใหม่</label>
                        <input type="password" 
                               name="confirm_password"
                               placeholder="ยืนยันรหัสผ่านใหม่"
                               minlength="6">
                    </div>

                    <div class="btn-group">
                        <button type="button" class="btn btn-secondary" onclick="window.location.href='index.php'">
                            ยกเลิก
                        </button>
                        <button type="submit" class="btn btn-primary">
                            บันทึกข้อมูล
                        </button>
                    </div>
                </form>
            </div>
        </div>
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