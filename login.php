

<?php include('api.php');?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RegisterPage</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include ('header.php');?>
    <br>
    <form action="datastructures.php?action=customer_login" method="POST">
    <div class="container">
        <h3 class="text-center">เข้าสู่ระบบ</h3>
        <label for="Username">ชื่อผู้ใช้</label>
        <input type="text" class="form-control" name="Username" required placeholder="โปรดระบุชื่อผู้ใช้ของคุณ">
        <label for="Password">รหัสผ่าน</label>
        <input type="password" class="form-control" name="Password" required placeholder="โปรดใส่รหัสผ่าน">
        <br>
        <button type="submit" class="btn btn-success" value="เข้าสู่ระบบ">เข้าสู่ระบบ</button>
    </div>
    </form>
    
    

    <?php include ('footer.php');?>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
</body>
</html>