<?php include('api.php'); ?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เข้าสู่ระบบ - โสภา คาเฟ่</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include('header.php'); ?>

    <div class="center-page">
        <div class="card-coffee register-card">
            <h3 class="text-center">เข้าสู่ระบบ</h3>

            <form action="datastructures.php?action=customer_login" method="POST" class="mt-4">
                <div class="form-group">
                    <label>ชื่อผู้ใช้</label>
                    <input type="text" name="Username" required placeholder="โปรดระบุชื่อผู้ใช้ของคุณ">
                </div>

                <div class="form-group">
                    <label>รหัสผ่าน</label>
                    <input type="password" name="Password" required placeholder="โปรดใส่รหัสผ่าน">
                </div>

                <button type="submit" class="btn-coffee w-100 mt-4">เข้าสู่ระบบ</button>
            </form>
        </div>
    </div>

    <?php include('footer.php'); ?>
</body>
</html>