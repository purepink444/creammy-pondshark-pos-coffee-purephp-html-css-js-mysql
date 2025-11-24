<?php include('api.php'); ?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>สมัครสมาชิก</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">

</head>
<body>

<?php include('header.php'); ?>

<div class="center-page">
    <div class="card-coffee register-card">

        <!-- avatar -->
        <div class="avatar-coffee mx-auto"></div>

        <h3 class="text-center mt-3">สมัครสมาชิก</h3>

        <form action="api_method.php?action=customer_register" method="POST" class="mt-4">

            <label class="form-label mt-3">ชื่อผู้ใช้</label>
            <input type="text" class="form-control input-coffee" name="Username" required placeholder="โปรดระบุชื่อผู้ใช้">

            <label class="form-label mt-3">ชื่อ-นามสกุล</label>
            <input type="text" class="form-control input-coffee" name="Fullname" required placeholder="โปรดระบุชื่อ-นามสกุล">

            <label class="form-label mt-3">อีเมล</label>
            <input type="email" class="form-control input-coffee" name="Email" required placeholder="โปรดระบุอีเมล">

            <label class="form-label mt-3">รหัสผ่าน</label>
            <input type="password" class="form-control input-coffee" name="Password" required placeholder="โปรดระบุรหัสผ่าน">

            <button type="submit" class="btn btn-coffee w-100 mt-4">สมัครสมาชิก</button>

        </form>
    </div>
</div>

<?php include('footer.php'); ?>

</body>
</html>
