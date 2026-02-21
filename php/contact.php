<?php
require_once("config.php");

if (isset($_POST['submit'])) {

    $fullname = $_POST['fullname'];
    $email    = $_POST['email'];
    $phone    = $_POST['phone'];
    $topic    = $_POST['topic'];
    $message  = $_POST['message'];

    $sql = "INSERT INTO contact (fullname, email, phone, topic, message)
            VALUES ('$fullname', '$email', '$phone', '$topic', '$message')";

    if (mysqli_query($conn, $sql)) {
        echo "Message saved successfully!";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>