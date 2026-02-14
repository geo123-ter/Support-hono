<?php
session_start();
include("db.php");

if(!isset($_SESSION['admin'])){
    header("Location: adminlogin.php");
    exit();
}

$result = $conn->query("SELECT * FROM  volunteers ORDER BY joined_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Volunteers</title>
<link rel="stylesheet" href="../css/joined.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
table { width:100%; border-collapse: collapse; margin-top:20px; }
th, td { padding:10px; border-bottom:1px solid #000000; text-align:left; }
th { background:#f5f5f5; }
tr:hover { background:#f1f1f1; }
</style>
</head>
<body>
<div class="side-bar">
    <div class="profile">
        <h3><?php echo $_SESSION['admin']; ?></h3>
    </div>
    <nav>
        <ul>
            <li><a href="./dashbord.php"><i class="fas fa-home"></i> Dashboard</a></li>
            <li><a href="./volunteerjoined.php"><i class="fas fa-user-plus"></i> Volunteers</a></li>
            <li><a href="./lougout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
    </nav>
</div>
<div class="main-content">
    <h1>Volunteers Joined</h1>
    <table>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Role</th>
            <th>Availability</th>
            <th>Skills</th>
            <th>Reason</th>
            <th>Background Check</th>
            <th>Agreed Terms</th>
            <th>Joined At</th>
        </tr>
        <?php while($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo $row['full_name']; ?></td>
            <td><?php echo $row['email']; ?></td>
            <td><?php echo $row['phone']; ?></td>
            <td><?php echo $row['role']; ?></td>
            <td><?php echo $row['availability']; ?></td>
            <td><?php echo $row['skills']; ?></td>
            <td><?php echo $row['reason']; ?></td>
            <td><?php echo $row['background_check'] ? 'Yes':'No'; ?></td>
            <td><?php echo $row['volunteer_terms'] ? 'Yes':'No'; ?></td>
            <td><?php echo $row['joined_at']; ?></td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>
</body>
</html>
