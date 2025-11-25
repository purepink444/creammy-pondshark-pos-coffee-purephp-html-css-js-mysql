<?php

$action = isset($_GET['action']) ? $_GET['action'] : '';

// Include PDO Database
include('api.php');  

    # code...
    switch ($action) {
        case 'user_register':
            # code...
                userRegister($conn);
            break;
        
        default:
            # code...
            break;
    }

           function userRegister($conn) {
                    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                    $username = $_POST['username'];
                    $name = $_POST['name'];
                    $email = $_POST['email'];
                    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

                        try {
                            //code...
                            $sql = "INSERT INTO users (username, name, email, password) VALUES (:username, :name, :email, :password)";
                            $stmt = $conn->prepare($sql);
                            $stmt->bindParam(':username', $username);
                            $stmt->bindParam(':name', $name);
                            $stmt->bindParam(':email', $email);
                            $stmt->bindParam(':password', $password);
                            $stmt->execute();
                            echo 'Register Success';
                        } catch (PDOException $e) {
                            //throw $th;
                            echo 'Register Failed' .$e->getMessage();
                        }
                    
                 }
            }



?>
