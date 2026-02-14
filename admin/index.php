<?php
session_start();

if(!isset($_SESSION['admin'])){
    header("Location: adminlogin.php");
    exit();
}


include("db.php");
$admin_username = $_SESSION['admin'];
$admin_result = $conn->query("SELECT * FROM admins WHERE username='$admin_username'");
$admin = $admin_result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard || Admin</title>
    <link rel="stylesheet" href="../css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>
   <div class="side-bar">
    <div class="profile">
      <div class="profile">
    <h3><?php echo $admin['username']; ?></h3>
</div>

    </div>

    <nav>
        <ul>
            <li><a href="./volunteerjoined.php">Volunteer Joined</a></li>
            <li><a href="#">Support</a></li>
            <li><a href="../php/supportre.php"><i class="fas fa-hands-helping"></i> People Need Support</a></li>
            <li><a href="../php/bloag.php">+ Add Bloag Post</a></li>
        </ul>
    </nav>

    <button class="logout-btn"><a href="./lougout.php">Log out</a></button>
</div>

<div class="main-content">
   <h1 class="expet"> Welcome <?php echo $admin['username']; ?></h1>
    <p>Manage your posts and testimonials here.</p>
</div>

</body>
</html>