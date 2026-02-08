<?php 
include ('php/db.php');


if (isset($_POST['register'])) {
    
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];


    $query = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$password')";

   
    if (mysqli_query($conn, $query)) {
        echo "<p style='color:green;'>New record created successfully </p>";
    } else {
        echo "<p style='color:red;'>Error: " . mysqli_error($conn) . "</p>";
    }
}
?>
