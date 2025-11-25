<?php

$action = isset($_GET['action']) ? $_GET['action'] : '';

// Include PDO Database
include('api.php');  // ต้องมี $pdo เชื่อม DB

/* ---------------------------------------------------
   ❖ 1. Quick Sort (รองรับข้อมูลแบบ numeric)
--------------------------------------------------- */

function partition(&$arr, $left, $right)
{
    $pivot = $arr[floor(($left + $right) / 2)];

    while ($left <= $right)
    {
        while ($arr[$left] < $pivot)  $left++;
        while ($arr[$right] > $pivot) $right--;

        if ($left <= $right) {
            $tmp = $arr[$left];
            $arr[$left] = $arr[$right];
            $arr[$right] = $tmp;
            $left++;
            $right--;
        }
    }
    return $left;
}

function quickSort(&$arr, $left, $right)
{
    $index = partition($arr, $left, $right);

    if ($left < $index - 1)
        quickSort($arr, $left, $index - 1);

    if ($index < $right)
        quickSort($arr, $index, $right);
}


/* ---------------------------------------------------
   ❖ 2. Binary Search (หลังจาก Sort แล้วเท่านั้น)
--------------------------------------------------- */

function binarySearch($arr, $target)
{
    $low = 0;
    $high = count($arr) - 1;

    while ($low <= $high) {
        $mid = floor(($low + $high) / 2);

        if ($arr[$mid] == $target) return true;
        elseif ($target < $arr[$mid]) $high = $mid - 1;
        else $low = $mid + 1;
    }
    return false;
}


/* ---------------------------------------------------
   ❖ 3. User Register + Validation + Hashing + PDO
--------------------------------------------------- */

function userRegister($pdo, $username, $name, $email, $password)
{
    // 1) ตรวจสอบข้อมูล
    if (empty($username) || empty($name) || empty($email) || empty($password))
        return "กรอกข้อมูลให้ครบทุกช่อง";

    if (!filter_var($email, FILTER_VALIDATE_EMAIL))
        return "อีเมลไม่ถูกต้อง";

    if (strlen($password) < 8)
        return "รหัสผ่านต้อง 8 ตัวขึ้นไป";

    // 2) ตรวจสอบซ้ำด้วย Binary Search (ผ่าน DB)
    $stmt = $pdo->query("SELECT email FROM customers ORDER BY email");
    $emails = $stmt->fetchAll(PDO::FETCH_COLUMN);

    // ถ้ามีรายการ → ใช้ quickSort ก่อน search
    if (count($emails) > 1) quickSort($emails, 0, count($emails)-1);

    if (binarySearch($emails, $email))
        return "อีเมลนี้มีอยู่แล้วในระบบ";

    // 3) Hashing
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    // 4) Insert Database ด้วย PDO
    $sql = "INSERT INTO customers (username, fullname, email, password)
            VALUES (:username, :fullname, :email, :password)";
    $stm = $pdo->prepare($sql);
    $stm->execute([
        ":username" => $username,
        ":fullname" => $name,
        ":email"    => $email,
        ":password" => $hashedPassword
    ]);

    return "ลงทะเบียนสำเร็จ!";
}


/* ---------------------------------------------------
   ❖ 4. Action
--------------------------------------------------- */

if ($action === "register" && $_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = $_POST['username'];
    $name     = $_POST['name'];
    $email    = $_POST['email'];
    $password = $_POST['password'];

    $result = userRegister($pdo, $username, $name, $email, $password);

    echo $result;
    exit;
}

?>
