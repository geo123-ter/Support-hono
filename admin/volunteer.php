<?php
// volunteer.php
include("db.php"); 

header('Content-Type: text/plain'); 

if($_SERVER["REQUEST_METHOD"] == "POST") {

    $full_name = trim($_POST['full_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $role = trim($_POST['role'] ?? '');
    $availability = trim($_POST['availability'] ?? '');
    $skills = trim($_POST['skills'] ?? '');
    $reason = trim($_POST['reason'] ?? '');

   
    if(empty($full_name) || empty($email) || empty($role)) {
        echo "error: Required fields missing";
        exit;
    }
    $stmt = $conn->prepare("INSERT INTO volunteers 
        (full_name, email, phone, role, availability, skills, reason) 
        VALUES (?, ?, ?, ?, ?, ?, ?)");

    if(!$stmt) {
        echo "error: Prepare failed - " . $conn->error;
        exit;
    }

    $stmt->bind_param("sssssss", $full_name, $email, $phone, $role, $availability, $skills, $reason);

    if($stmt->execute()) {
        echo "success";
    } else {
        echo "error: Execute failed - " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
