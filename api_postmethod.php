<?php
include('api.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        // Get POST Data
        $Username = trim($_POST['Username']);
        $Firstname = trim($_POST['Firstname']);
        $Lastname = trim($_POST['Lastname']);
        $Email = trim($_POST['Email']);
        $Password = password_hash($_POST['Password'], PASSWORD_BCRYPT);
        $RoleID = $_POST['RoleID'] ?? 103; // Default: 103 Member

        // Validate Data
        if (empty($Username) || empty($Firstname) || empty($Lastname) || empty($Email) || empty($_POST['Password'])) {
            throw new Exception('กรุณากรอกข้อมูลให้ครบถ้วน');
        }

        // Check for existing Username or Email
        $sql = "SELECT COUNT(*) FROM Users WHERE Username = :Username OR Email = :Email";
        $checkStmt = $conn->prepare($sql);
        $checkStmt->bindParam(':Username', $Username);
        $checkStmt->bindParam(':Email', $Email);
        $checkStmt->execute();

        if ($checkStmt->fetchColumn() > 0) {
            throw new Exception('Username หรือ Email นี้มีในระบบแล้ว');
        }

        $sql = "INSERT INTO Users (Username, Firstname, Lastname, Email, Password, RoleID) 
                VALUES (:Username, :Firstname, :Lastname, :Email, :Password, :RoleID)";
        
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':Username', $Username);
        $stmt->bindParam(':Firstname', $Firstname);
        $stmt->bindParam(':Lastname', $Lastname);
        $stmt->bindParam(':Email', $Email);
        $stmt->bindParam(':Password', $Password);
        $stmt->bindParam(':RoleID', $RoleID); 

        if ($stmt->execute()) {
            echo "<script>alert('สมัครสมาชิกสำเร็จ'); window.location.href='login.php';</script>";
        } else {
            throw new Exception('ไม่สามารถบันทึกข้อมูลได้');
        }

    } catch (Exception $e) {
        echo "<script>alert('" . $e->getMessage() . "'); window.location.href='register.php';</script>";
    }
}
?>