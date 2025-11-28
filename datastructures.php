<?php

$action = isset($_GET['action']) ? $_GET['action'] : '';

// Include PDO Database
include('api.php');  

    # code...
    switch ($action) {
        case 'customer_register':
            # code...
                customerRegister($conn);
            break;
        case 'customer_login':
            # code...
                customerLogin($conn);
            break;
        case 'admin_register':
            # code...
                adminRegister($conn);
            break;
        default:
            # code...
            break;
    }

           function customerRegister($conn) {
                    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                    $Username = $_POST['Username'];
                    $Name = $_POST['Name'];
                    $Phone = $_POST['Phone'];
                    $Email = $_POST['Email'];
                    $Password = password_hash($_POST['Password'], PASSWORD_DEFAULT);
                    $Points = $_POST['Points'];

                        try {
                            //code...
                            $sql = "INSERT INTO customer (Username, Name, Phone, Email, Password, Points) VALUES (:Username, :Name, :Phone, :Email, :Password, :Points)";
                            $stmt = $conn->prepare($sql);
                            $stmt->bindParam(':Username', $Username);
                            $stmt->bindParam(':Name', $Name);
                            $stmt->bindParam(':Phone', $Phone);
                            $stmt->bindParam(':Email', $Email);
                            $stmt->bindParam(':Password', $Password);
                            $stmt->bindParam(':Points', $Points);
                            $stmt->execute();
                            echo 'Register Success';
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

                            } catch (PDOException $e) {
                            //throw $th;
                            echo 'Login Failed' .$e->getMessage();
                        }
                    }
            }

            function adminRegister($conn) {
                    
            }

?>
