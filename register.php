<?php include('api.php'); ?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>สมัครสมาชิก</title>
    <link rel="stylesheet" href="style.css">

</head>
<body>

<?php include('header.php'); ?>

<div class="center-page">
    <div class="card-coffee register-card">

        <h3 class="text-center mt-3">สมัครสมาชิก</h3>

        <form action="datastructures.php?action=customer_register" method="POST" class="mt-4">

            <div class="form-group">
                <label>ชื่อผู้ใช้</label>
                <input type="text" name="Username" required placeholder="โปรดระบุชื่อผู้ใช้">
            </div>

            <div class="form-group">
                <label>คำนำหน้าชื่อ</label>
                <select name="Prefix" required>
                    <option value="นาย">นาย</option>
                    <option value="นางสาว">นางสาว</option>
                    <option value="นาง">นาง</option>
                </select>
            </div>

            <div class="form-group">
                <label>ชื่อ-นามสกุล</label>
                <input type="text" name="Name" required placeholder="โปรดระบุชื่อ-นามสกุล">
            </div>

            <div class="form-group">
                <label>อีเมล</label>
                <input type="email" name="Email" required placeholder="โปรดระบุอีเมล">
            </div>

            <div class="form-group">
                <label>รหัสผ่าน</label>
                <input type="password" name="Password" required placeholder="โปรดระบุรหัสผ่าน">
            </div>

            <button type="submit" class="btn-coffee w-100 mt-4">สมัครสมาชิก</button>

        </form>
    </div>
</div>

<?php include('footer.php'); ?>

</body>
</html>
