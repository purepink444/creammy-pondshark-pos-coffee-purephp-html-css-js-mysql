<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);


$action = isset($_GET['action']) ? $_GET['action'] : '';

// Include PDO Database
include('api.php');  

    # code...
    switch ($action) {
        case 'customer_register':
            # code...
                customerRegister($conn);
                header('Location: menu.php');
            break;
        case 'customer_login':
            # code...
                customerLogin($conn);
                header('Location: menu.php');
            break;
        case 'employee_register':
            # code...
                employeeRegister($conn);
            break;
        case 'employee_login':
            # code...
                employeeLogin($conn);
            break;
        default:
            # code...
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
                    $Points = $_POST['Points'];

                        try {
                            //code...
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
                            header('Location: menu.php');
                        } catch (PDOException $e) {
                            //throw $th;
                            echo 'Register Failed' .$e->getMessage();
                        }
                    
                 }
            }

            function customerLogin($conn) {
                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                        # code...
                        $Username = $_POST['Username'];
                        $Password = password_hash($_POST['Password'], PASSWORD_DEFAULT);

                        try {
                            //code...
                            $sql = "SELECT * FROM customer WHERE Username = :Username";
                            $stmt = $conn->prepare($sql);
                            $stmt->bindParam(':Username', $Username);
                            $stmt->bindParam(':Password', $Password);
                            $stmt->execute();
                            
                            header('Location: menu.php');
                            } catch (PDOException $e) {
                            //throw $th;
                            echo 'Login Failed' .$e->getMessage();
                        }
                    }
            }

            function employeeRegister($conn) {
                    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                        # code...
                        $Username = $_POST['Username'];
                        $Prefix = $_POST['Prefix'];
                        $Name = $_POST['Name'];
                        $Phone = $_POST['Phone'];
                        $Email = $_POST['Email'];
                        $Password = password_hash($_POST['Password'], PASSWORD_DEFAULT);
                        $Role = $_POST['Role'];
                        $Status = $_POST['Status'];

                        try {
                            //code...
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
                        } catch (PDOException $e) {
                            //throw $th;
                            echo 'Register Failed' .$e->getMessage();
                        }
                    }
            }

            function employeeLogin($conn) {
                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                        # code...
                        $Username = $_POST['Username'];
                        $Password = password_hash($_POST['Password'], PASSWORD_DEFAULT);

                        try {
                            //code...
                            $sql = "SELECT * FROM employee WHERE Username = :Username";
                            $stmt = $conn->prepare($sql);
                            $stmt->bindParam(':Username', $Username);
                            $stmt->bindParam(':Password', $Password);
                            $stmt->execute();

                            } catch (PDOException $e) {
                            //throw $th;
                            echo 'Login Failed' .$e->getMessage();
                        }
                    }
            }
            
            



?>
