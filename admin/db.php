<?php
$servername = "sql205.infinityfree.com";
$username = "if0_41158582";
$password = "tf4ySlvOTU81k4u";
$database = "if0_41158582_user_portal";

$conn = new mysqli($servername, $username, $password, $database);

if($conn->connect_error){
    die("Connection failed: " . $conn->connect_error);
} else {
    // echo "Connection successful!";
}
?>
