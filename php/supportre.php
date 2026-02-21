<?php
session_start();
include("config.php");

if(!isset($_SESSION['admin'])){
    header("Location: ../admin/adminlogin.php");
    exit();
}

$result = $conn->query("SELECT * FROM support_requests ORDER BY submitted_at DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>People Need Support</title>
<link rel="stylesheet" href="../css/supporter.css">
<style>
table { width:100%; border-collapse: collapse; margin-top:20px; }
th, td { padding:10px; border-bottom:1px solid #ddd; text-align:left; }
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
            <li><a href="../admin/dashbord.php" <?php echo (basename($_SERVER['PHP_SELF']) == 'dashbord.php') ? 'class="active"' : ''; ?>>
                <i class="fas fa-home"></i> Dashboard
            </a></li>
            <li><a href="../admin/volunteerjoined.php" <?php echo (basename($_SERVER['PHP_SELF']) == 'volunteerjoined.php') ? 'class="active"' : ''; ?>>
                <i class="fas fa-user-plus"></i> Volunteers
            </a></li>
            <li><a href="../php/supportre.php" <?php echo (basename($_SERVER['PHP_SELF']) == 'supportre.php') ? 'class="active"' : ''; ?>>
                <i class="fas fa-hand-holding-heart"></i> People Need Support
            </a></li>
            <li><a href="../admin/lougout.php">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a></li>
        </ul>
    </nav>
</div>
<div class="main-content">
    <h1>People Need Support</h1>
    
    <!-- Stats Summary -->
    <div class="support-stats">
        <?php
        $total = $conn->query("SELECT COUNT(*) as count FROM support_requests")->fetch_assoc()['count'];
        $recent = $conn->query("SELECT COUNT(*) as count FROM support_requests WHERE submitted_at >= DATE_SUB(NOW(), INTERVAL 24 HOUR)")->fetch_assoc()['count'];
        ?>
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-info">
                <h3>Total Requests</h3>
                <div class="number"><?php echo $total; ?></div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-clock"></i>
            </div>
            <div class="stat-info">
                <h3>Last 24 Hours</h3>
                <div class="number"><?php echo $recent; ?></div>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="filter-section">
        <select id="need-filter">
            <option value="">All Needs</option>
            <option value="Mobility Aid">Mobility Aid</option>
            <option value="Tutoring">Tutoring</option>
            <option value="Event">Event</option>
            <option value="Communication">Communication</option>
            <option value="Other">Other</option>
        </select>
        <input type="text" id="search" placeholder="Search by name or email...">
        <button class="filter-btn" onclick="filterTable()">
            <i class="fas fa-filter"></i> Apply Filters
        </button>
    </div>

    <table id="support-table">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Type of Need</th>
            <th>Details</th>
            <th>Submitted At</th>
        </tr>
        <?php while($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo htmlspecialchars($row['full_name']); ?></td>
            <td><?php echo htmlspecialchars($row['email']); ?></td>
            <td><?php echo htmlspecialchars($row['phone']); ?></td>
            <td>
                <span class="status-indicator status-pending"></span>
                <?php echo htmlspecialchars($row['need']); ?>
            </td>
            <td>
                <div class="details-wrapper">
                    <span class="preview"><?php echo substr(htmlspecialchars($row['details']), 0, 50) . '...'; ?></span>
                    <span class="full-details"><?php echo nl2br(htmlspecialchars($row['details'])); ?></span>
                </div>
            </td>
            <td><?php echo date('M d, Y H:i', strtotime($row['submitted_at'])); ?></td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>

<script>
function filterTable() {
    var needFilter = document.getElementById('need-filter').value;
    var searchTerm = document.getElementById('search').value.toLowerCase();
    var table = document.getElementById('support-table');
    var rows = table.getElementsByTagName('tr');
    
    for (var i = 1; i < rows.length; i++) {
        var row = rows[i];
        var need = row.cells[4].textContent.trim();
        var name = row.cells[1].textContent.toLowerCase();
        var email = row.cells[2].textContent.toLowerCase();
        
        var needMatch = needFilter === '' || need === needFilter;
        var searchMatch = searchTerm === '' || name.includes(searchTerm) || email.includes(searchTerm);
        
        if (needMatch && searchMatch) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    }
}
</script>
</div>
</body>
</html>
