<?php
include('api.php');

$action = $_GET['action'] ?? ($_POST['action'] ?? '');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    switch ($action) {

        case 'customer_register':
            try {
                $Name = $_POST['Name'];
                $Phone = $_POST['Phone'];
                $Points = $_POST['Points'];

                $sql = "INSERT INTO customer (Name, Phone, Points) 
                        VALUES (:Name, :Phone, :Points)";
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':Name', $Name);
                $stmt->bindParam(':Phone', $Phone);
                $stmt->bindParam(':Points', $Points);
                $stmt->execute();

                echo "New Customer Registered successfully";
            } catch (PDOException $e) {
                echo 'Register Error: ' . $e->getMessage();
            }
            break;

        case 'customer_query':
            try {
                $Phone = $_POST['Phone'];

                $sql = "SELECT * FROM customer WHERE Phone = :Phone LIMIT 1";
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':Phone', $Phone);
                $stmt->execute();

                if ($stmt->rowCount() > 0) {
                    echo "Customer Login successfully";
                } else {
                    echo "Phone number not found";
                }
            } catch (PDOException $e) {
                echo 'Login Error: ' . $e->getMessage();
            }
            break;
         
        case 'employee_register':
            # code...
            break;

        case 'employee_login':
            # code...
            break;
            
        default:
            echo "Invalid action";
            break;
    }
}
?>
