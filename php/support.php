<?php
include("config.php");

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $full_name = $conn->real_escape_string($_POST['full_name']);
    $email = $conn->real_escape_string($_POST['email']);
    $phone = $conn->real_escape_string($_POST['phone']);
    $need = $conn->real_escape_string($_POST['need']);
    $details = $conn->real_escape_string($_POST['details']);

    $sql = "INSERT INTO support_requests (full_name, email, phone, need, details)
            VALUES ('$full_name','$email','$phone','$need','$details')";

    if($conn->query($sql) === TRUE){
        echo "Your request has been submitted successfully!";
    } else {
        echo "Error: " . $conn->error;
    }

    $conn->close();
}
?>
