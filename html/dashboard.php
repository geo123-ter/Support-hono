<?php
session_start();

// Database connection
$conn = new mysqli("localhost", "root", "", "user_portal");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check login
if (!isset($_SESSION['user_id'])) {
    header("Location: ../userlogin.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];
$stmt = $conn->prepare("SELECT COUNT(*) as my_support_count FROM support_requests WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user_stats = $result->fetch_assoc();
$volunteers_result = $conn->query("SELECT COUNT(*) AS my_volunteer_count FROM volunteers");
$user_stats['my_volunteer_count'] = $volunteers_result->fetch_assoc()['my_volunteer_count'] ?? 0;
$announcements_result = $conn->query("
    SELECT COUNT(*) AS new_announcements 
    FROM announcements 
    WHERE created_at > DATE_SUB(NOW(), INTERVAL 7 DAY)
");
$new_announcements = $announcements_result->fetch_assoc()['new_announcements'] ?? 0;
$total_notifications = $new_announcements + ($user_stats['my_support_count'] ?? 0);
$my_volunteers = $conn->query("
    SELECT * FROM volunteers
    ORDER BY joined_at DESC
    LIMIT 5
");
$stmt2 = $conn->prepare("
    SELECT * FROM support_requests
    WHERE id = ?
    ORDER BY submitted_at DESC
    LIMIT 5
");
$stmt2->bind_param("i", $user_id);
$stmt2->execute();
$my_support = $stmt2->get_result();

$announcements = $conn->query("
    SELECT * FROM announcements
    ORDER BY 
        CASE priority
            WHEN 'high' THEN 1
            WHEN 'medium' THEN 2
            WHEN 'low' THEN 3
        END,
        created_at DESC
    LIMIT 5
");
// New announcements
$announcements_result = $conn->query("
    SELECT COUNT(*) AS new_announcements 
    FROM announcements 
    WHERE created_at > DATE_SUB(NOW(), INTERVAL 7 DAY)
");

$new_announcements = $announcements_result->fetch_assoc()['new_announcements'] ?? 0;
$user_stats['new_announcements'] = $new_announcements;

// Pending requests
$pending_result = $conn->query("
    SELECT COUNT(*) AS pending_requests
    FROM support_requests
    WHERE status = 'Pending'
");

$pending_requests = $pending_result->fetch_assoc()['pending_requests'] ?? 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome <?php echo htmlspecialchars($username); ?> - User Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Copy ALL styles from admin dashboard - they are the same */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
        }

        /* Sidebar styling - slightly different colors for user */
        .side-bar {
            width: 280px;
            background: linear-gradient(180deg, #1a472a 0%, #0e2a1a 100%);
            color: white;
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            box-shadow: 4px 0 20px rgba(0, 0, 0, 0.2);
            display: flex;
            flex-direction: column;
            animation: slideIn 0.5s ease;
            z-index: 1000;
        }

        @keyframes slideIn {
            from {
                transform: translateX(-100%);
            }
            to {
                transform: translateX(0);
            }
        }

        /* Profile Section */
        .profile {
            padding: 30px 20px;
            text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            background: rgba(255, 255, 255, 0.05);
        }

        .profile h3 {
            color: white;
            font-size: 1.4em;
            font-weight: 400;
        }

        .profile p {
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.85em;
            margin-top: 5px;
        }

        /* Navigation Menu */
        nav {
            flex: 1;
            padding: 20px 0;
        }

        nav ul {
            list-style: none;
        }

        nav ul li {
            margin: 5px 0;
        }

        nav ul li a {
            display: flex;
            align-items: center;
            padding: 15px 25px;
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            font-size: 1.1em;
            transition: all 0.3s ease;
            border-left: 4px solid transparent;
        }

        nav ul li a i {
            margin-right: 15px;
            width: 24px;
            text-align: center;
            font-size: 1.2em;
            color: #4caf50;
        }

        nav ul li a:hover {
            background: rgba(255, 255, 255, 0.1);
            color: white;
            border-left: 4px solid #4caf50;
            padding-left: 35px;
        }

        nav ul li a.active {
            background: rgba(76, 175, 80, 0.15);
            color: white;
            border-left: 4px solid #4caf50;
        }

        /* Main Content Area */
        .main-content {
            margin-left: 280px;
            padding: 30px 40px;
            flex: 1;
            min-height: 100vh;
            background: #f8f9fa;
            animation: fadeIn 0.5s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Welcome Header */
        .welcome-header {
            background: white;
            border-radius: 20px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .welcome-text h1 {
            color: #1a1a2e;
            font-size: 2.5em;
            margin-bottom: 10px;
        }

        .welcome-text h1 span {
            color: #4caf50;
        }

        .welcome-text p {
            color: #4a5568;
            font-size: 1.2em;
        }

        /* Notification Bell */
        .notification-bell {
            position: relative;
            cursor: pointer;
            font-size: 1.8em;
            color: #4caf50;
            transition: transform 0.3s ease;
        }

        .notification-bell:hover {
            transform: scale(1.1);
        }

        .notification-badge {
            position: absolute;
            top: -10px;
            right: -10px;
            background: #f44336;
            color: white;
            border-radius: 50%;
            padding: 2px 8px;
            font-size: 0.6em;
            font-weight: bold;
        }

        /* Stats Cards - Personal stats */
        .stats-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }

        .stat-card {
            background: white;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            cursor: pointer;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #4caf50, #45a049);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 20px;
        }

        .stat-icon i {
            color: white;
            font-size: 28px;
        }

        .stat-info h3 {
            color: #4a5568;
            font-size: 1em;
            margin-bottom: 8px;
        }

        .stat-info .number {
            color: #1a1a2e;
            font-size: 2.5em;
            font-weight: 600;
        }

        .stat-info .small-text {
            color: #718096;
            font-size: 0.85em;
        }

        /* Announcement Card */
        .announcement-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }

        .announcement-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #eef2f6;
        }

        .announcement-header h2 {
            color: #1a1a2e;
            font-size: 1.4em;
        }

        .announcement-header h2 i {
            margin-right: 10px;
            color: #4caf50;
        }

        .announcement-list {
            max-height: 300px;
            overflow-y: auto;
        }

        .announcement-item {
            padding: 15px;
            margin-bottom: 10px;
            border-radius: 10px;
            background: #f8f9fa;
            border-left: 4px solid #4caf50;
        }

        .announcement-item.high {
            border-left-color: #f44336;
        }

        .announcement-item.medium {
            border-left-color: #ff9800;
        }

        .announcement-item.low {
            border-left-color: #4caf50;
        }

        .announcement-title {
            font-weight: 600;
            color: #1a1a2e;
            margin-bottom: 5px;
            display: flex;
            justify-content: space-between;
        }

        .priority-badge {
            font-size: 0.7em;
            padding: 2px 8px;
            border-radius: 20px;
        }

        .priority-badge.high {
            background: #f44336;
            color: white;
        }

        .priority-badge.medium {
            background: #ff9800;
            color: white;
        }

        .priority-badge.low {
            background: #4caf50;
            color: white;
        }

        .announcement-content {
            color: #4a5568;
            font-size: 0.9em;
            margin-bottom: 5px;
        }

        .announcement-meta {
            color: #a0aec0;
            font-size: 0.7em;
        }

        /* Activity Cards */
        .recent-activity {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .activity-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .activity-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #eef2f6;
        }

        .activity-header h2 {
            color: #1a1a2e;
            font-size: 1.4em;
        }

        .activity-header h2 i {
            margin-right: 10px;
            color: #4caf50;
        }

        .view-all {
            color: #4caf50;
            text-decoration: none;
            font-size: 0.9em;
        }

        .activity-item {
            display: flex;
            align-items: center;
            padding: 12px 0;
            border-bottom: 1px solid #eef2f6;
        }

        .activity-avatar {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #4caf50, #45a049);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            color: white;
            font-weight: 600;
        }

        .activity-details {
            flex: 1;
        }

        .activity-details h4 {
            color: #1a1a2e;
            font-size: 1em;
            margin-bottom: 4px;
        }

        .activity-details p {
            color: #718096;
            font-size: 0.85em;
        }

        .activity-time {
            color: #a0aec0;
            font-size: 0.8em;
        }

        .status-badge {
            padding: 2px 8px;
            border-radius: 20px;
            font-size: 0.7em;
            font-weight: 500;
        }

        .status-pending {
            background: #fff3e0;
            color: #ff9800;
        }

        .status-approved {
            background: #e8f5e9;
            color: #4caf50;
        }

        .status-resolved {
            background: #e3f2fd;
            color: #2196f3;
        }

        /* Quick Actions */
        .quick-actions {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
        }

        .action-btn {
            background: white;
            padding: 20px;
            border-radius: 12px;
            text-decoration: none;
            color: #1a1a2e;
            display: flex;
            align-items: center;
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }

        .action-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
            border-color: #4caf50;
        }

        .action-btn i {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #4caf50, #45a049);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            margin-right: 15px;
            font-size: 18px;
        }

        /* Notification Panel */
        .notification-panel {
            display: none;
            position: absolute;
            top: 80px;
            right: 40px;
            width: 350px;
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            z-index: 1001;
        }

        .notification-header {
            padding: 15px 20px;
            background: linear-gradient(135deg, #4caf50, #45a049);
            color: white;
            border-radius: 15px 15px 0 0;
        }

        .notification-list {
            max-height: 400px;
            overflow-y: auto;
        }

        .notification-item {
            padding: 15px 20px;
            border-bottom: 1px solid #eef2f6;
        }

        .notification-title {
            font-weight: 600;
            color: #1a1a2e;
            margin-bottom: 5px;
        }

        .notification-message {
            color: #718096;
            font-size: 0.85em;
        }

        @media screen and (max-width: 768px) {
            .side-bar {
                width: 100%;
                height: auto;
                position: relative;
            }
            .main-content {
                margin-left: 0;
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="side-bar">
        <div class="profile">
            <h3><?php echo htmlspecialchars($username); ?></h3>
            <p>Member since <?php echo date('M Y', strtotime($_SESSION['joined_at'] ?? 'now')); ?></p>
        </div>
        <nav>
            <ul>
                <li><a href="./dashboard.php" class="active"><i class="fas fa-home"></i> Dashboard</a></li>
                <li><a href="profile.php"><i class="fas fa-user"></i> My Profile</a></li>
                <li><a href="../php/supportre.php"><i class="fas fa-hand-holding-heart"></i> My Support Requests</a></li>
                <li><a href="./volunterr.html"><i class="fas fa-user-plus"></i> Become a Volunteer</a></li>
                <li><a href="./lougout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </nav>
    </div>

    <div class="main-content">
        <div class="welcome-header">
            <div class="welcome-text">
                <h1>Welcome back, <span><?php echo htmlspecialchars($username); ?></span>!</h1>
                <p>Track your contributions and support requests.</p>
            </div>
            <div class="notification-bell" onclick="toggleNotifications()">
                <i class="fas fa-bell"></i>
                <?php if($total_notifications > 0): ?>
                <span class="notification-badge"><?php echo $total_notifications; ?></span>
                <?php endif; ?>
            </div>
        </div>

        <!-- Notification Panel -->
        <div id="notificationPanel" class="notification-panel">
            <div class="notification-header">
                <i class="fas fa-bell"></i> Notifications (<?php echo $total_notifications; ?>)
            </div>
            <div class="notification-list">
                <?php if($user_stats['new_announcements'] > 0): ?>
                <div class="notification-item">
                    <div class="notification-title">New Announcements</div>
                    <div class="notification-message"><?php echo $user_stats['new_announcements']; ?> new announcement(s) this week.</div>
                </div>
                <?php endif; ?>
                <?php if($pending_requests > 0): ?>
                <div class="notification-item">
                    <div class="notification-title">Pending Requests</div>
                    <div class="notification-message">You have <?php echo $pending_requests; ?> pending support request(s).</div>
                </div>
                <?php endif; ?>
                <?php if($total_notifications == 0): ?>
                <div class="notification-item">
                    <div class="notification-title">All Caught Up!</div>
                    <div class="notification-message">No new notifications at this time.</div>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Personal Stats Cards -->
        <div class="stats-container">
            <div class="stat-card" onclick="location.href='volunteer-apply.php'">
                <div class="stat-icon">
                    <i class="fas fa-hands-helping"></i>
                </div>
                <div class="stat-info">
                    <h3>My Volunteer Activities</h3>
                    <div class="number"><?php echo $user_stats['my_volunteer_count']; ?></div>
                    <div class="small-text">Times I've volunteered</div>
                </div>
            </div>
            
            <div class="stat-card" onclick="location.href='my-requests.php'">
                <div class="stat-icon">
                    <i class="fas fa-hand-holding-heart"></i>
                </div>
                <div class="stat-info">
                    <h3>My Support Requests</h3>
                    <div class="number"><?php echo $user_stats['my_support_count']; ?></div>
                    <div class="small-text">Requests for help</div>
                </div>
            </div>
            
            <div class="stat-card" onclick="location.href='profile.php'">
                <div class="stat-icon">
                    <i class="fas fa-user-check"></i>
                </div>
                <div class="stat-info">
                    <h3>Account Status</h3>
                    <div class="number">Active</div>
                    <div class="small-text">Member since <?php echo date('M Y', strtotime($_SESSION['joined_at'] ?? 'now')); ?></div>
                </div>
            </div>
        </div>

        <!-- Announcements (same as admin but read-only) -->
        <div class="announcement-card">
            <div class="announcement-header">
                <h2><i class="fas fa-bullhorn"></i> Announcements</h2>
            </div>
            <div class="announcement-list">
                <?php if($announcements && $announcements->num_rows > 0): ?>
                    <?php while($ann = $announcements->fetch_assoc()): ?>
                    <div class="announcement-item <?php echo $ann['priority']; ?>">
                        <div class="announcement-title">
                            <span><?php echo htmlspecialchars($ann['title']); ?></span>
                            <span class="priority-badge <?php echo $ann['priority']; ?>">
                                <?php echo ucfirst($ann['priority']); ?>
                            </span>
                        </div>
                        <div class="announcement-content">
                            <?php echo htmlspecialchars($ann['content']); ?>
                        </div>
                        <div class="announcement-meta">
                            <i class="fas fa-calendar"></i> <?php echo date('M d, Y', strtotime($ann['created_at'])); ?>
                        </div>
                    </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="announcement-item">
                        <div class="announcement-title">No Announcements</div>
                        <div class="announcement-content">Check back later for updates.</div>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- User's Recent Activity -->
        <div class="recent-activity">
            <div class="activity-card">
                <div class="activity-header">
                    <h2><i class="fas fa-user-plus"></i> My Volunteer Activities</h2>
                    <a href="volunteer-apply.php" class="view-all">Apply <i class="fas fa-arrow-right"></i></a>
                </div>
                <ul class="activity-list">
                    <?php if($my_volunteers && $my_volunteers->num_rows > 0): ?>
                        <?php while($vol = $my_volunteers->fetch_assoc()): ?>
                        <li class="activity-item">
                            <div class="activity-avatar">
                                <i class="fas fa-hands-helping"></i>
                            </div>
                            <div class="activity-details">
                                <h4><?php echo htmlspecialchars($vol['role']); ?></h4>
                                <p><?php echo htmlspecialchars($vol['full_name']); ?></p>
                            </div>
                            <div class="activity-time">
                                <?php echo date('M d, Y', strtotime($vol['joined_at'])); ?>
                            </div>
                        </li>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <li class="activity-item">
                            <div class="activity-avatar">
                                <i class="fas fa-info"></i>
                            </div>
                            <div class="activity-details">
                                <h4>No volunteer activities yet</h4>
                                <p>Click "Apply" to become a volunteer</p>
                            </div>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>

            
        </div>

        <!-- Quick Actions -->
        <div class="quick-actions">
            <a href="./volunterr.html" class="action-btn">
                <i class="fas fa-user-plus"></i>
                <span>Become a Volunteer</span>
            </a>
            <a href="./request.html" class="action-btn">
                <i class="fas fa-hand-holding-heart"></i>
                <span>Request Support</span>
            </a>
            <a href="profile.php" class="action-btn">
                <i class="fas fa-user-edit"></i>
                <span>Edit Profile</span>
            </a>
        </div>
    </div>

    <script>
        function toggleNotifications() {
            const panel = document.getElementById('notificationPanel');
            panel.style.display = panel.style.display === 'block' ? 'none' : 'block';
        }

        document.addEventListener('click', function(event) {
            const panel = document.getElementById('notificationPanel');
            const bell = document.querySelector('.notification-bell');
            if (bell && !bell.contains(event.target) && panel && !panel.contains(event.target)) {
                panel.style.display = 'none';
            }
        });
    </script>
</body
</html>