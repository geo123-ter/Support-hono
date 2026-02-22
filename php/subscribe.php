<?php
include('config.php');

if(isset($_POST['submit'])){
    // Sanitize inputs
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['Subscribe']);

    $sql = "INSERT INTO subscribe (name, email) VALUES ('$name', '$email')";

    if(mysqli_query($conn, $sql)){
        echo "Subscribed successfully!";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>