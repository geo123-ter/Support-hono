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

$query = "SELECT * FROM users WHERE id='$id'";
$result = mysqli_query($conn, $query);
$student = mysqli_fetch_assoc($result);


if (isset($_POST['update'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];

    $updateQuery = "UPDATE users SET username='$username', email='$email' WHERE id='$id'";
    mysqli_query($conn, $updateQuery);

    header("Location: dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit ||</title>
    <link rel="stylesheet" href="edit.css">
</head>
<body>
    <form method="post">
    <h2>Edit Student</h2>
    <input type="text" name="username" value="<?php echo $student['username']; ?>" required>
    <input type="email" name="email" value="<?php echo $student['email']; ?>" required>
    <input type="submit" name="update" value="Update">
</form>
</body>
</html>
