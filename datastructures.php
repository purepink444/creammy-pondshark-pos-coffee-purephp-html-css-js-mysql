<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', '/pos-php-pdo/error.log');

session_start(); 

$action = isset($_GET['action']) ? $_GET['action'] : '';

include('api.php');  

switch ($action) {
    case 'customer_register':
        customerRegister($conn);
        break;
    case 'customer_login':
        customerLogin($conn);
        break;
    case 'employee_register':
        employeeRegister($conn);
        break;
    case 'employee_login':
        employeeLogin($conn);
        break;
    default:
        echo 'Invalid action';
        break;
}

// ==============================
// ลงทะเบียนลูกค้า
// ==============================
function customerRegister($conn) {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $Username = trim($_POST['Username']);
        $Prefix = $_POST['Prefix'];
        $Name = trim($_POST['Name']);
        $Phone = trim($_POST['Phone']);
        $Email = trim($_POST['Email']);
        $Password = password_hash($_POST['Password'], PASSWORD_DEFAULT);
        $Points = 0;

        try {
            // ตรวจสอบว่า Username ซ้ำหรือไม่
            $checkSql = "SELECT Username FROM customer WHERE Username = :Username";
            $checkStmt = $conn->prepare($checkSql);
            $checkStmt->bindParam(':Username', $Username);
            $checkStmt->execute();
            
            if ($checkStmt->rowCount() > 0) {
                echo '<script>alert("ชื่อผู้ใช้นี้มีอยู่แล้ว"); window.location.href="register.php";</script>';
                exit();
            }

            $sql = "INSERT INTO customer (Username, Prefix, Name, Phone, Email, Password, Points) 
                    VALUES (:Username, :Prefix, :Name, :Phone, :Email, :Password, :Points)";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':Username', $Username);
            $stmt->bindParam(':Prefix', $Prefix);
            $stmt->bindParam(':Name', $Name);
            $stmt->bindParam(':Phone', $Phone);
            $stmt->bindParam(':Email', $Email);
            $stmt->bindParam(':Password', $Password);
            $stmt->bindParam(':Points', $Points);
            $stmt->execute();
            
            echo '<script>alert("สมัครสมาชิกสำเร็จ"); window.location.href="login.php";</script>';
            exit();
        } catch (PDOException $e) {
            echo 'Register Failed: ' . $e->getMessage();
        }
    }
}

// ==============================
// เข้าสู่ระบบลูกค้า
// ==============================
function customerLogin($conn) {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $Username = trim($_POST['Username']);
        $Password = $_POST['Password'];

        try {
            $sql = "SELECT * FROM customer WHERE Username = :Username";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':Username', $Username);
            $stmt->execute();
            
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // ตรวจสอบว่าพบผู้ใช้หรือไม่
            if (!$user) {
                echo '<script>alert("ไม่พบผู้ใช้นี้ในระบบ"); window.location.href="login.php";</script>';
                exit();
            }
            
            // ตรวจสอบรหัสผ่าน
            if (password_verify($Password, $user['Password'])) {
                // ตั้งค่า Session
                $_SESSION['CustomerID'] = $user['CustomerID'];
                $_SESSION['Username'] = $user['Username'];
                $_SESSION['Name'] = $user['Name'];
                $_SESSION['Prefix'] = $user['Prefix'];
                $_SESSION['Phone'] = $user['Phone'];
                $_SESSION['Email'] = $user['Email'];
                $_SESSION['Points'] = $user['Points'];
                
                header('Location: menu.php');
                exit();
            } else {
                echo '<script>alert("รหัสผ่านไม่ถูกต้อง"); window.location.href="login.php";</script>';
                exit();
            }
        } catch (PDOException $e) {
            echo 'Login Failed: ' . $e->getMessage();
        }
    }
}

// ==============================
// ลงทะเบียนพนักงาน
// ==============================
function employeeRegister($conn) {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $Username = trim($_POST['Username']);
        $Prefix = $_POST['Prefix'];
        $Name = trim($_POST['Name']);
        $Phone = trim($_POST['Phone']);
        $Email = trim($_POST['Email']);
        $Password = password_hash($_POST['Password'], PASSWORD_DEFAULT);
        $Role = $_POST['Role'];
        $Status = isset($_POST['Status']) ? $_POST['Status'] : 'active';

        try {
            // ตรวจสอบว่า Username ซ้ำหรือไม่
            $checkSql = "SELECT Username FROM employee WHERE Username = :Username";
            $checkStmt = $conn->prepare($checkSql);
            $checkStmt->bindParam(':Username', $Username);
            $checkStmt->execute();
            
            if ($checkStmt->rowCount() > 0) {
                echo '<script>alert("ชื่อผู้ใช้นี้มีอยู่แล้ว"); window.location.href="employee_register.php";</script>';
                exit();
            }

            $sql = "INSERT INTO employee (Username, Prefix, Name, Phone, Email, Password, Role, Status) 
                    VALUES (:Username, :Prefix, :Name, :Phone, :Email, :Password, :Role, :Status)";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':Username', $Username);
            $stmt->bindParam(':Prefix', $Prefix);
            $stmt->bindParam(':Name', $Name);
            $stmt->bindParam(':Phone', $Phone);
            $stmt->bindParam(':Email', $Email);
            $stmt->bindParam(':Password', $Password);
            $stmt->bindParam(':Role', $Role);
            $stmt->bindParam(':Status', $Status);
            $stmt->execute();
            
            echo '<script>alert("สมัครสมาชิกสำเร็จ"); window.location.href="employee_login.php";</script>';
            exit();
        } catch (PDOException $e) {
            echo 'Register Failed: ' . $e->getMessage();
        }
    }
}

// ==============================
// เข้าสู่ระบบพนักงาน
// ==============================
function employeeLogin($conn) {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $Username = trim($_POST['Username']);
        $Password = $_POST['Password'];

        try {
            $sql = "SELECT * FROM employee WHERE Username = :Username AND Status = 'active'";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':Username', $Username);
            $stmt->execute();
            
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // ตรวจสอบว่าพบผู้ใช้หรือไม่
            if (!$user) {
                echo '<script>alert("ไม่พบผู้ใช้นี้ในระบบหรือบัญชีถูกระงับ"); window.location.href="employee_login.php";</script>';
                exit();
            }
            
            // ตรวจสอบรหัสผ่าน
            if (password_verify($Password, $user['Password'])) {
                // ตั้งค่า Session
                $_SESSION['EmployeeID'] = $user['EmployeeID'];
                $_SESSION['Username'] = $user['Username'];
                $_SESSION['Name'] = $user['Name'];
                $_SESSION['Prefix'] = $user['Prefix'];
                $_SESSION['Phone'] = $user['Phone'];
                $_SESSION['Email'] = $user['Email'];
                $_SESSION['Role'] = $user['Role'];
                $_SESSION['Status'] = $user['Status'];
                
                header('Location: orders.php');
                exit();
            } else {
                echo '<script>alert("รหัสผ่านไม่ถูกต้อง"); window.location.href="employee_login.php";</script>';
                exit();
            }
        } catch (PDOException $e) {
            echo 'Login Failed: ' . $e->getMessage();
        }
    }
}
?>