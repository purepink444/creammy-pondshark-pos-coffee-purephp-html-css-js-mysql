<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', '/pos-php-pdo/error.log');
trigger_error("This is a test error message.", E_USER_WARNING);

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

function customerRegister($conn) {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $Username = $_POST['Username'];
        $Prefix = $_POST['Prefix'];
        $Name = $_POST['Name'];
        $Phone = $_POST['Phone'];
        $Email = $_POST['Email'];
        $Password = password_hash($_POST['Password'], PASSWORD_DEFAULT);
        $Points = 0; // ตั้งค่าเริ่มต้นเป็น 0

        try {
            $sql = "INSERT INTO customer (Username, Prefix, Name, Phone, Email, Password, Points) VALUES (:Username, :Prefix, :Name, :Phone, :Email, :Password, :Points)";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':Username', $Username);
            $stmt->bindParam(':Prefix', $Prefix);
            $stmt->bindParam(':Name', $Name);
            $stmt->bindParam(':Phone', $Phone);
            $stmt->bindParam(':Email', $Email);
            $stmt->bindParam(':Password', $Password);
            $stmt->bindParam(':Points', $Points);
            $stmt->execute();
            
            echo 'Register Success';
            header('Location: login.php');
            exit();
        } catch (PDOException $e) {
            echo 'Register Failed: ' . $e->getMessage();
        }
    }
}

function customerLogin($conn) {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $Username = $_POST['Username'];
        $Password = $_POST['Password']; // ไม่ต้อง hash ตอนนี้!

        try {
            $sql = "SELECT * FROM customer WHERE Username = :Username";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':Username', $Username);
            $stmt->execute();
            
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // ตรวจสอบรหัสผ่าน
            if ($user && password_verify($Password, $user['Password'])) {
                // ตั้งค่า Session
                $_SESSION['CustomerID'] = $user['CustomerID'];
                $_SESSION['Username'] = $user['Username'];
                $_SESSION['Name'] = $user['Name'];
                $_SESSION['Prefix'] = $user['Prefix'];
                $_SESSION['Phone'] = $user['Phone'];
                $_SESSION['Email'] = $user['Email'];
                $_SESSION['Points'] = $user['Points'];
                
                echo 'Login Success!';
                header('Location: menu.php');
                exit();
            } else {
                echo 'Login Failed: Invalid username or password';
            }
        } catch (PDOException $e) {
            echo 'Login Failed: ' . $e->getMessage();
        }
    }
}

function employeeRegister($conn) {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $Username = $_POST['Username'];
        $Prefix = $_POST['Prefix'];
        $Name = $_POST['Name'];
        $Phone = $_POST['Phone'];
        $Email = $_POST['Email'];
        $Password = password_hash($_POST['Password'], PASSWORD_DEFAULT);
        $Role = $_POST['Role'];
        $Status = isset($_POST['Status']) ? $_POST['Status'] : 'active';

        try {
            $sql = "INSERT INTO employee (Username, Prefix, Name, Phone, Email, Password, Role, Status) VALUES (:Username, :Prefix, :Name, :Phone, :Email, :Password, :Role, :Status)";
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
            
            echo 'Register Success';
            header('Location: employee_login.php');
            exit();
        } catch (PDOException $e) {
            echo 'Register Failed: ' . $e->getMessage();
        }
    }
}

function employeeLogin($conn) {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $Username = $_POST['Username'];
        $Password = $_POST['Password']; // ไม่ต้อง hash ตอนนี้!

        try {
            $sql = "SELECT * FROM employee WHERE Username = :Username";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':Username', $Username);
            $stmt->execute();
            
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // ตรวจสอบรหัสผ่าน
            if ($user && password_verify($Password, $user['Password'])) {
                // ตั้งค่า Session
                $_SESSION['EmployeeID'] = $user['EmployeeID'];
                $_SESSION['Username'] = $user['Username'];
                $_SESSION['Name'] = $user['Name'];
                $_SESSION['Prefix'] = $user['Prefix'];
                $_SESSION['Phone'] = $user['Phone'];
                $_SESSION['Email'] = $user['Email'];
                $_SESSION['Role'] = $user['Role'];
                $_SESSION['Status'] = $user['Status'];
                
                echo 'Login Success!';
                header('Location: orders.php');
                exit();
            } else {
                echo 'Login Failed: Invalid username or password';
            }
        } catch (PDOException $e) {
            echo 'Login Failed: ' . $e->getMessage();
        }
    }
}
?>