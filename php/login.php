<?php
include './php/db.php'; 

session_start();
$_SESSION['username'] = $username;


if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

 
    $query = "SELECT * FROM users WHERE username='$username'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);

        
        if ($password === $user['password']) { 
            $_SESSION['username'] = $username;
            echo "<p style='color:green;'>Login successful! Welcome, $username </p>";

            header("Location: ./das/dashboard.php");
        } else {
            echo "<p style='color:red;'>Incorrect password </p>";
        }
    } else {
        echo "<p style='color:red;'>User not found </p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LOGIN||<?= date("Y:M") ?></title>

    <style>
    body {
      font-family: "Poppins", sans-serif;
      background: linear-gradient(135deg, #007acc, #00c6ff);
      color: white;
      min-height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
    }
    form {
      background: rgba(0, 0, 0, 0.2);
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
      display: flex;
      flex-direction: column;
      gap: 15px;
      width: 300px;
      height: 300px;
    }
    input[type="text"], input[type="password"] {
      padding: 10px;
        border: none;
        border-radius: 5px;
        font-size: 16px;
        outline: none;

    }
    input[type="submit"] {
      background: #ffdd57;
      color: #333;
      border: none;
      padding: 10px;
      border-radius: 5px;
      cursor: pointer;
      font-weight: bold;
      transition: 0.3s;
      margin-top: 10px;
    }
    input[type="submit"]:hover {
      background: #e6c24f;
    }
     h2 {
      text-align: center;
      margin-bottom: 20px;
    }
p{
        text-align: center;
        font: 100;

}
p a {
        color: #ffdd57;
        text-decoration: none;
}

    </style>
</head>
<body>
     <form action="" method="post">
        <h2>Login</h2>
        <input type="text" name="username" id="username" required placeholder=" Enter your Username">
        <input type="password" name="password" id="password" required placeholder=" Enter your Password">
        <input type="submit" value="Login" name="login">

        <p>I dont have an account <a href="./register.php">Register here</a></p>
     </form>
</body>
</html>