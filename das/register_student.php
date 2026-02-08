<?php
session_start();
include '../php/db.php';

// Redirect if not logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$message = "";

// Handle form submission
if (isset($_POST['register'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
   

    // Check if username or email already exists
    $checkQuery = "SELECT * FROM users WHERE username='$username' OR email='$email'";
    $checkResult = mysqli_query($conn, $checkQuery);

    if (mysqli_num_rows($checkResult) > 0) {
        $message = "Username or Email already exists!";
    } else {
        // Insert new student
        $insertQuery = "INSERT INTO users (username, email) VALUES ('$username', '$email')";
        if (mysqli_query($conn, $insertQuery)) {
            $message = "Student registered successfully!";
        } else {
            $message = "Error: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Register New Student</title>
<link rel="stylesheet" href="dash.css">
</head>
<body>

<div class="side-bar">
    <h2>Welcome, <?php echo $_SESSION['username']; ?>!</h2>
    <nav>
        <a href="dashboard.php"><i class="fa-solid fa-house"></i> Dashboard</a>
        <a href="logout.php"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
    </nav>
</div>

<section class="main-content">
    <h1>Register New Student</h1>

    <?php if ($message != ""): ?>
        <p class="message"><?php echo $message; ?></p>
    <?php endif; ?>

    <form method="post">
        <input type="text" name="username" placeholder="Enter Username" required>
        <input type="email" name="email" placeholder="Enter Email" required>
        <input type="submit" name="register" value="Register Student" class="btn">
    </form>
</section>

</body>
</html>
