<?php
session_start();
include '../php/db.php';


if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}


if (!isset($_GET['id'])) {
    header("Location: dashboard.php");
    exit();
}

$id = $_GET['id'];


if (!is_numeric($id)) {
    header("Location: dashboard.php");
    exit();
}


$deleteQuery = "DELETE FROM users WHERE id='$id'";
if (mysqli_query($conn, $deleteQuery)) {
    
    header("Location: dashboard.php?message=Student+deleted+successfully");
    exit();
} else {
    echo "Error deleting student: " . mysqli_error($conn);
}
?>
