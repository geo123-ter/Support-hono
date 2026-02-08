<?php
session_start();
include '../php/db.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Fetch donations
$query = "SELECT * FROM donations ORDER BY created_at DESC";
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>NGO Admin Dashboard</title>

<link rel="stylesheet" href="./dash.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">
</head>
<body>

<!-- Sidebar -->
<div class="side-bar">
    <h2>NGO Admin</h2>
    <p class="welcome">Welcome, <?php echo $_SESSION['username']; ?></p>

    <nav>
        <a href="dashboard.php" class="active">
            <i class="fa-solid fa-gauge"></i> Dashboard
        </a>
        <a href="projects.php">
            <i class="fa-solid fa-hand-holding-heart"></i> Projects
        </a>
        <a href="donations.php">
            <i class="fa-solid fa-donate"></i> Donations
        </a>
        <a href="volunteers.php">
            <i class="fa-solid fa-users"></i> Volunteers
        </a>
        <a href="settings.php">
            <i class="fa-solid fa-gear"></i> Settings
        </a>
    </nav>

    <a href="../logout.php" class="btn-logout">
        <i class="fa-solid fa-right-from-bracket"></i> Logout
    </a>
</div>

<!-- Main Content -->
<section class="main-content">
    <h1>Donations Overview</h1>
    <p>Recent donations made to the NGO</p>

    <table>
        <tr>
            <th>ID</th>
            <th>Donor Name</th>
            <th>Email</th>
            <th>Amount</th>
            <th>Date</th>
        </tr>

        <?php if (mysqli_num_rows($result) > 0): ?>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo $row['name']; ?></td>
                <td><?php echo $row['email']; ?></td>
                <td>$<?php echo number_format($row['amount'], 2); ?></td>
                <td><?php echo $row['created_at']; ?></td>
            </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="5" style="text-align:center;">
                    No donations yet
                </td>
            </tr>
        <?php endif; ?>
    </table>
</section>

</body>
</html>
