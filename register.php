<?php include('api.php'); ?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CustomerRegister</title>
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
me="Name" required placeholder="โปรดระบุชื่อของคุณ">

            <label class="form-label mt-3">เบอร์โทรศัพท์</label>
        <form action="api_method.php?action=customer_register" method="POST" class="mt-4">

            <label class="form-label">ชื่อ</label>
            <input type="text" class="form-control input-coffee" na
            <input type="text" class="form-control input-coffee" name="Phone" required placeholder="โปรดระบุเบอร์โทรศัพท์">

            <label class="form-label mt-3">คะแนนสะสม</label>
            <input type="text" class="form-control input-coffee" name="Points" required placeholder="คะแนนสะสม">

            <button type="submit" class="btn btn-coffee w-100 mt-4">สมัครสมาชิก</button>

        </form>
    </div>
</div>

<?php include('footer.php'); ?>

</body>
</html>
