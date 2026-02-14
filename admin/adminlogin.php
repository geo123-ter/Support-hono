<?php
session_start();
include("db.php");

$error = '';

if(isset($_POST['login'])){
    $username = $conn->real_escape_string($_POST['username']);
    $password = $conn->real_escape_string($_POST['password']);

    $sql = "SELECT * FROM admins WHERE username='$username'";
    $result = $conn->query($sql);

    if($result->num_rows > 0){
        $row = $result->fetch_assoc();
        if($password === $row['password']) { // plain password for now
            $_SESSION['admin'] = $row['username'];
            header("Location: dashbord.php");
            exit();
        } else {
            $error = "Incorrect password!";
        }
    } else {
        $error = "Admin not found!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin Login</title>
<style>
body {
    font-family: "Poppins", sans-serif;
    background: linear-gradient(135deg, #667eea, #764ba2);
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    margin: 0;
}

.login-form {
    background: rgba(255, 255, 255, 0.95);
    padding: 40px 30px;
    border-radius: 15px;
    width: 420px;
    box-shadow: 0 20px 40px rgba(0,0,0,0.15);
    backdrop-filter: blur(10px);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.login-form:hover {
    transform: translateY(-5px);
    box-shadow: 0 25px 50px rgba(0,0,0,0.2);
}

.login-form h2 {
    text-align: center;
    margin-bottom: 25px;
    color: #2c3e50;
    font-size: 1.8rem;
}

.login-form input {
    width: 100%;
    /* padding: 14px 15px; */height: 50px;
    margin-bottom: 20px;
    border: 2px solid #ddd;
    border-radius: 12px;
    font-size: 1rem;
    transition: all 0.3s ease;
    background: rgba(255,255,255,0.9);
}

.login-form input:focus {
    border-color: #2ecc71;
    box-shadow: 0 0 8px rgba(46,204,113,0.2);
    outline: none;
}

.login-form button {
    width: 100%;
    padding: 14px;
    background: linear-gradient(135deg, #2ecc71, #27ae60);
    border: none;
    border-radius: 12px;
    color: #fff;
    font-weight: 600;
    font-size: 1.1rem;
    cursor: pointer;
    transition: all 0.3s ease;
}

.login-form button:hover {
    background: linear-gradient(135deg, #27ae60, #2ecc71);
    box-shadow: 0 10px 25px rgba(46,204,113,0.4);
    transform: translateY(-2px);
}

.error {
    color: #e74c3c;
    text-align: center;
    margin-bottom: 15px;
    background: #fdecea;
    padding: 10px 12px;
    border-radius: 10px;
    border-left: 4px solid #e74c3c;
    font-size: 0.95rem;
}

/* Responsive */
@media (max-width: 400px) {
    .login-form {
        width: 90%;
        padding: 30px 20px;
    }
}
.warning {
    background: #fff3cd;
    color: #856404;
    border: 1px solid #ffeeba;
    padding: 12px 15px;
    border-radius: 10px;
    font-size: 0.95rem;
    margin-top: 15px;
    text-align: center;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
}

.warning i {
    font-size: 1.1rem;
}

.go {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    margin-top: 20px;
    padding: 10px 18px;
    background: #3498db;
    color: #fff;
    text-decoration: none;
    font-weight: 600;
    border-radius: 10px;
    transition: all 0.3s ease;
}

.go:hover {
    background: #2980b9;
    transform: translateY(-2px);
    box-shadow: 0 6px 15px rgba(41,128,185,0.3);
}

.go i {
    font-size: 0.9rem;
}

</style>
</head>
<body>
<form   class="login-form" method="POST" >
    <h2>Admin Login</h2>
    <?php if($error) { echo "<div class='error'>$error</div>"; } ?>
    <input type="text" name="username" placeholder="Username" required>
    <input type="password" name="password" placeholder="Password" required>
    <button name="login">Login</button>
    <p class="warning">
    <i class="fas fa-exclamation-triangle"></i>
    Warning: If you are not an admin, this is not your login — please step back! 
</p>
<a href="../index.php" class="go">
    <i class="fas fa-arrow-left"></i> Go back
</a>

</form>
</body>
</html>
