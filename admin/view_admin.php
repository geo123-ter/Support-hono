<?php
session_start();
include("db.php");

// check login
if(!isset($_SESSION['admin'])){
    header("Location: adminlogin.php");
    exit();
}

// check if id exists
if(!isset($_GET['id'])){
    echo "No admin selected!";
    exit();
}

$id = intval($_GET['id']); // safer

// fetch admin
$query = "SELECT * FROM admins WHERE id = $id";
$result = mysqli_query($conn, $query);
$admin = mysqli_fetch_assoc($result);

if(!$admin){
    echo "Admin not found!";
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Admin</title>
    <style>
        body {
            font-family: Arial;
            background: #f4f6f9;
            padding: 40px;
        }
        .card {
            background: white;
            padding: 30px;
            border-radius: 15px;
            max-width: 500px;
            margin: auto;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }
        h2 {
            margin-bottom: 20px;
        }
        p {
            margin: 10px 0;
            font-size: 16px;
        }
        .back {
            margin-top: 20px;
            display: inline-block;
            padding: 10px 15px;
            background: #667eea;
            color: white;
            border-radius: 8px;
            text-decoration: none;
        }
    </style>
</head>
<body>

<div class="card">
    <h2> Admin Details</h2>

    <p><strong>ID:</strong> #<?php echo $admin['id']; ?></p>
    <p><strong>Username:</strong> <?php echo htmlspecialchars($admin['username']); ?></p>
    <p><strong>Status:</strong> Active</p>
    <p><strong>Created:</strong> <?php echo date('M d, Y', strtotime($admin['created_at'])); ?></p>

    <?php if($admin['username'] == $_SESSION['admin']): ?>
        <p><strong>Note:</strong> This is you </p>
    <?php endif; ?>

    <a href="./admin.php" class="back">Back</a>
</div>

</body>
</html>