<?php
session_start();
include("db.php");

if(!isset($_SESSION['admin'])){
    header("Location: adminlogin.php");
    exit();
}

// Get statistics for dashboard
$total_volunteers = $conn->query("SELECT COUNT(*) as count FROM volunteers")->fetch_assoc()['count'];
$total_support = $conn->query("SELECT COUNT(*) as count FROM support_requests")->fetch_assoc()['count'];
// $total_blog_posts = $conn->query("SELECT COUNT(*) as count FROM blog_posts")->fetch_assoc()['count'];
$recent_volunteers = $conn->query("SELECT * FROM volunteers ORDER BY joined_at DESC LIMIT 5");
$recent_support = $conn->query("SELECT * FROM support_requests ORDER BY submitted_at DESC LIMIT 5");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Reset and base styles */
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

        /* Sidebar styling */
        .side-bar {
            width: 280px;
            background: linear-gradient(180deg, #1a1a2e 0%, #16213e 100%);
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
            position: relative;
            overflow: hidden;
        }

        .profile::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            animation: rotate 20s linear infinite;
        }

        @keyframes rotate {
            from {
                transform: rotate(0deg);
            }
            to {
                transform: rotate(360deg);
            }
        }

        .profile h3 {
            color: white;
            font-size: 1.4em;
            font-weight: 400;
            letter-spacing: 1px;
            position: relative;
            display: inline-block;
            margin: 0;
            padding: 10px 0;
        }

        .profile h3::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 50px;
            height: 3px;
            background: linear-gradient(90deg, #667eea, #764ba2);
            border-radius: 2px;
            transition: width 0.3s ease;
        }

        .profile:hover h3::after {
            width: 80px;
        }

        /* Navigation Menu */
        nav {
            flex: 1;
            padding: 20px 0;
            overflow-y: auto;
        }

        nav ul {
            list-style: none;
            margin: 0;
            padding: 0;
        }

        nav ul li {
            margin: 5px 0;
            position: relative;
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
            position: relative;
            overflow: hidden;
        }

        nav ul li a::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent);
            transition: left 0.5s ease;
        }

        nav ul li a:hover::before {
            left: 100%;
        }

        nav ul li a i {
            margin-right: 15px;
            width: 24px;
            text-align: center;
            font-size: 1.2em;
            color: #667eea;
            transition: all 0.3s ease;
        }

        nav ul li a:hover {
            background: rgba(255, 255, 255, 0.1);
            color: white;
            border-left: 4px solid #667eea;
            padding-left: 35px;
        }

        nav ul li a:hover i {
            transform: scale(1.1);
            color: white;
        }

        /* Active link state */
        nav ul li a.active {
            background: rgba(102, 126, 234, 0.15);
            color: white;
            border-left: 4px solid #667eea;
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
            position: relative;
            overflow: hidden;
        }

        .welcome-header::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 200px;
            height: 200px;
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
            border-radius: 50%;
            transform: translate(50%, -50%);
        }

        .welcome-header h1 {
            color: #1a1a2e;
            font-size: 2.5em;
            margin-bottom: 10px;
            font-weight: 400;
            position: relative;
        }

        .welcome-header h1 span {
            color: #667eea;
            font-weight: 600;
        }

        .welcome-header p {
            color: #4a5568;
            font-size: 1.2em;
            line-height: 1.6;
            max-width: 600px;
            position: relative;
        }

        /* Stats Cards */
        .stats-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
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
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.05) 0%, rgba(118, 75, 162, 0.05) 100%);
            transform: translateX(-100%);
            transition: transform 0.5s ease;
        }

        .stat-card:hover::before {
            transform: translateX(0);
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 20px;
            position: relative;
            z-index: 1;
        }

        .stat-icon i {
            color: white;
            font-size: 28px;
        }

        .stat-info {
            position: relative;
            z-index: 1;
        }

        .stat-info h3 {
            color: #4a5568;
            font-size: 1em;
            margin-bottom: 8px;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .stat-info .number {
            color: #1a1a2e;
            font-size: 2.5em;
            font-weight: 600;
            line-height: 1;
        }

        .stat-info .small-text {
            color: #718096;
            font-size: 0.9em;
            margin-top: 5px;
        }

        /* Recent Activity Section */
        .recent-activity {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 20px;
            margin-top: 30px;
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
            font-weight: 500;
        }

        .activity-header h2 i {
            margin-right: 10px;
            color: #667eea;
        }

        .view-all {
            color: #667eea;
            text-decoration: none;
            font-size: 0.9em;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .view-all:hover {
            color: #764ba2;
            transform: translateX(5px);
        }

        /* Activity List */
        .activity-list {
            list-style: none;
        }

        .activity-item {
            display: flex;
            align-items: center;
            padding: 12px 0;
            border-bottom: 1px solid #eef2f6;
            transition: all 0.3s ease;
        }

        .activity-item:last-child {
            border-bottom: none;
        }

        .activity-item:hover {
            background: #f8faff;
            padding-left: 10px;
            border-radius: 8px;
        }

        .activity-avatar {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #667eea, #764ba2);
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

        /* Quick Actions */
        .quick-actions {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-top: 30px;
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
            border-color: #667eea;
        }

        .action-btn i {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            margin-right: 15px;
            font-size: 18px;
        }

        .action-btn span {
            font-weight: 500;
        }

        /* Responsive Design */
        @media screen and (max-width: 1024px) {
            .side-bar {
                width: 240px;
            }
            
            .main-content {
                margin-left: 240px;
                padding: 20px;
            }
            
            .welcome-header h1 {
                font-size: 2em;
            }
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
            
            .stats-container {
                grid-template-columns: 1fr;
            }
            
            .recent-activity {
                grid-template-columns: 1fr;
            }
            
            .welcome-header h1 {
                font-size: 1.8em;
            }
            
            .welcome-header p {
                font-size: 1em;
            }
        }

        @media screen and (max-width: 480px) {
            .main-content {
                padding: 15px;
            }
            
            .stat-card {
                padding: 20px;
            }
            
            .stat-icon {
                width: 50px;
                height: 50px;
                margin-right: 15px;
            }
            
            .stat-icon i {
                font-size: 24px;
            }
            
            .stat-info .number {
                font-size: 2em;
            }
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        ::-webkit-scrollbar-thumb {
            background: linear-gradient(135deg, #667eea, #764ba2);
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(135deg, #764ba2, #667eea);
        }

        /* Loading Animation */
        .loading {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(102, 126, 234, 0.3);
            border-radius: 50%;
            border-top-color: #667eea;
            animation: spin 1s ease-in-out infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <div class="side-bar">
        <div class="profile">
            <h3><?php echo $_SESSION['admin']; ?></h3>
        </div>
        <nav>
            <ul>
                <li><a href="../admin/dashbord.php" class="active"><i class="fas fa-home"></i> Dashboard</a></li>
                <li><a href="../admin/volunteerjoined.php"><i class="fas fa-user-plus"></i> Volunteers</a></li>
                <li><a href="../php/supportre.php"><i class="fas fa-hand-holding-heart"></i> People Need Support</a></li>
                <li><a href="../html/annoucement.html"><i class="fa-solid fa-bullhorn"></i>Annoucement</a></li>
                <li><a href="../admin/lougout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </nav>
    </div>

    <div class="main-content">
        <div class="welcome-header">
            <h1>Welcome back, <span><?php echo $_SESSION['admin']; ?></span>!</h1>
            <p>Here's what's happening with your platform today.</p>
        </div>

        <div class="stats-container">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-info">
                    <h3>Total Volunteers</h3>
                    <div class="number"><?php echo $total_volunteers; ?></div>
                    <div class="small-text">Active members</div>
                </div>
            </div>
        </div>

        <div class="recent-activity">
            <div class="activity-card">
                <div class="activity-header">
                    <h2><i class="fas fa-user-plus"></i> Recent Volunteers</h2>
                    <a href="../admin/adminlogin.php" class="view-all">View All <i class="fas fa-arrow-right"></i></a>
                </div>
                <ul class="activity-list">
                    <?php while($vol = $recent_volunteers->fetch_assoc()): ?>
                    <li class="activity-item">
                        <div class="activity-avatar">
                            <?php echo strtoupper(substr($vol['full_name'], 0, 2)); ?>
                        </div>
                        <div class="activity-details">
                            <h4><?php echo $vol['full_name']; ?></h4>
                            <p><?php echo $vol['role']; ?></p>
                        </div>
                        <div class="activity-time">
                            <?php echo date('M d', strtotime($vol['joined_at'])); ?>
                        </div>
                    </li>
                    <?php endwhile; ?>
                </ul>
            </div>

            <div class="activity-card">
    <div class="activity-header">
        <h2><i class="fas fa-hand-holding-heart"></i> Recent Support</h2>
        <a href="../php/supportre.php" class="view-all">View All <i class="fas fa-arrow-right"></i></a>
    </div>
    <ul class="activity-list">
        <?php 
        if($recent_support && $recent_support->num_rows > 0):
            while($support = $recent_support->fetch_assoc()): ?>
            <li class="activity-item">
                <div class="activity-avatar">
                    <?php echo strtoupper(substr($support['full_name'], 0, 2)); ?>
                </div>
                <div class="activity-details">
                    <h4><?php echo htmlspecialchars($support['full_name']); ?></h4>
                    <p><?php echo htmlspecialchars($support['need']); ?></p>
                </div>
                <div class="activity-time">
                    <?php echo date('M d', strtotime($support['submitted_at'])); ?>
                </div>
            </li>
        <?php 
            endwhile;
        else: ?>
            <li class="activity-item">
                <div class="activity-avatar">!</div>
                <div class="activity-details">
                    <h4>No support requests yet</h4>
                    <p>There are currently no people needing support.</p>
                </div>
                <div class="activity-time">—</div>
            </li>
        <?php endif; ?>
    </ul>
</div>

        </div>

        <div class="quick-actions">
            <a href="volunteers.php" class="action-btn">
                <i class="fas fa-user-plus"></i>
                <span>View Volunteers</span>
            </a>
            <a href="support_requests.php" class="action-btn">
                <i class="fas fa-hand-holding-heart"></i>
                <span>Check Support</span>
            </a>
        </div>
    </div>
</body>
</html>