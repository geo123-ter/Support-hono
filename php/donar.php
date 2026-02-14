<?php
session_start();
include("config.php"); 

$response = ['status' => 'error', 'message' => 'Unknown error'];

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $phone = $conn->real_escape_string($_POST['phone']);
    $amount = floatval($_POST['amount']);
    $donation_type = $conn->real_escape_string($_POST['donation_type']);
    $recurring_interval = isset($_POST['recurring_interval']) ? $conn->real_escape_string($_POST['recurring_interval']) : NULL;
    $message = $conn->real_escape_string($_POST['message']);

    if(empty($name) || empty($email) || empty($phone) || $amount <= 0){
        $response['message'] = "Please fill all required fields correctly.";
    } else {
      
        $stmt = $conn->prepare("INSERT INTO donors (name, email, phone, amount, donation_type, recurring_interval, message) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssisss", $name, $email, $phone, $amount, $donation_type, $recurring_interval, $message);

        if($stmt->execute()){
            $response['status'] = 'success';
            $response['message'] = 'Thank you for your donation!';
            
        } else {
            $response['message'] = "Failed to record donation. Please try again later.";
        }
        $stmt->close();
    }
}

$conn->close();


if($response['status'] === 'success'){
    echo "<script>alert('".$response['message']."'); window.location.href='donar.php';</script>";
} else {
    echo "<script>alert('".$response['message']."'); window.history.back();</script>";
}
?>
