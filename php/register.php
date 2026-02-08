<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register || <?= date("Y:M:D") ?></title>

<style>
/* === Global Reset === */
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
  font-family: "Poppins", sans-serif;
}

/* === Body Styling === */
body {
  background: linear-gradient(135deg, #007acc, #004c99);
  height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
}

/* === Form Container === */
form {
  background: #fff;
  padding: 40px 35px;
  border-radius: 15px;
  box-shadow: 0 8px 25px rgba(0, 0, 0, 0.25);
  width: 380px;
  transition: all 0.3s ease;
}

form:hover {
  transform: translateY(-5px);
}

/* === Heading === */
form h2 {
  text-align: center;
  color: #007acc;
  margin-bottom: 25px;
  letter-spacing: 1px;
}

/* === Input Fields === */
input[type="text"],
input[type="email"],
input[type="password"] {
  width: 100%;
  padding: 12px 15px;
  margin: 10px 0;
  border: 1px solid #ccc;
  border-radius: 8px;
  outline: none;
  font-size: 15px;
  transition: 0.3s;
}

input:focus {
  border-color: #007acc;
  box-shadow: 0 0 8px rgba(0, 122, 204, 0.3);
}

/* === Submit Button === */
input[type="submit"] {
  width: 100%;
  background: #007acc;
  color: #fff;
  font-size: 16px;
  font-weight: 600;
  border: none;
  border-radius: 8px;
  padding: 12px;
  margin-top: 15px;
  cursor: pointer;
  transition: 0.3s;
}

input[type="submit"]:hover {
  background: #005f99;
  box-shadow: 0 5px 15px rgba(0, 122, 204, 0.3);
}

/* === Form Bottom Link (Optional) === */
form p {
  text-align: center;
  margin-top: 15px;
  font-size: 14px;
}

form p a {
  color: #007acc;
  text-decoration: none;
  font-weight: 500;
}

form p a:hover {
  text-decoration: underline;
}


@media (max-width: 420px) {
  form {
    width: 90%;
    padding: 30px 20px;
  }
}
</style>

</head>
<body>
    <form action="./insert.php" method="post">
        <h2>REGISTER HERE</h2>

        <input type="text" name="username" id="name1" placeholder="Enter Username" required>
        <input type="email" name="email" id="email1" placeholder="Enter Email" required>
        <input type="password" name="password" id="pass1" placeholder="Enter Password" required>
        <input type="password" name="cpassword" id="cpass1" placeholder="Confirm Password" required>
        <input type="submit" value="Register" name="register"><br>

    </form>
</body>
</html>