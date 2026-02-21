<?php
session_start();
include("db.php");
include('config.php');

// Check if admin is logged in
if(!isset($_SESSION['admin'])) {
    header("Location: ../admin/adminlogin.php");
    exit();
}

$sql = "SELECT * FROM contact ORDER BY created_at DESC";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes">
    <title>Admin Dashboard - Contact Messages</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f6fa;
            min-height: 100vh;
        }

        /* Mobile First Approach */
        .container {
            display: flex;
            flex-direction: column;
        }

        /* Mobile Navigation */
        .mobile-nav {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: white;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .menu-toggle {
            background: none;
            border: none;
            color: white;
            font-size: 1.5rem;
            cursor: pointer;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            transition: background 0.3s;
        }

        .menu-toggle:hover {
            background: rgba(255,255,255,0.1);
        }

        .mobile-title {
            font-size: 1.1rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .mobile-title i {
            font-size: 1.2rem;
        }

        .mobile-user {
            width: 40px;
            height: 40px;
            background: rgba(255,255,255,0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
        }

        /* Sidebar */
        .sidebar {
            position: fixed;
            top: 0;
            left: -280px;
            width: 280px;
            height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            transition: left 0.3s ease;
            z-index: 1001;
            overflow-y: auto;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
        }

        .sidebar.active {
            left: 0;
        }

        .sidebar-header {
            padding: 20px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        .sidebar-header h3 {
            color: white;
            font-size: 1.3rem;
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 10px;
        }

        .sidebar-header p {
            color: rgba(255,255,255,0.8);
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .sidebar-menu {
            list-style: none;
            padding: 10px 0;
        }

        .sidebar-menu li {
            margin: 5px 0;
        }

        .sidebar-menu a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 15px 20px;
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            transition: all 0.3s;
            border-left: 3px solid transparent;
        }

        .sidebar-menu a:hover,
        .sidebar-menu a.active {
            background: rgba(255,255,255,0.1);
            color: white;
            border-left-color: white;
        }

        .sidebar-menu a i {
            width: 20px;
        }

        /* Overlay */
        .overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 1000;
        }

        .overlay.active {
            display: block;
        }

        /* Main Content */
        .main-content {
            flex: 1;
            padding: 15px;
            width: 100%;
        }

        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 15px;
            margin-bottom: 20px;
        }

        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .stat-icon {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
        }

        .stat-info h3 {
            font-size: 1.5rem;
            color: #333;
            margin-bottom: 5px;
        }

        .stat-info p {
            color: #666;
            font-size: 0.9rem;
        }

        /* Table Container */
        .table-container {
            background: white;
            border-radius: 15px;
            padding: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.05);
            overflow-x: auto;
        }

        /* Responsive Table */
        .responsive-table {
            width: 100%;
            border-collapse: collapse;
            min-width: 600px; /* Ensures table doesn't get too small */
        }

        .responsive-table th {
            background: #f8f9fa;
            color: #495057;
            font-weight: 600;
            padding: 15px 12px;
            text-align: left;
            border-bottom: 2px solid #dee2e6;
            font-size: 0.9rem;
            white-space: nowrap;
        }

        .responsive-table td {
            padding: 12px;
            border-bottom: 1px solid #e9ecef;
            color: #495057;
            font-size: 0.9rem;
        }

        .responsive-table tr:hover {
            background: #f8f9fa;
        }

        /* Mobile Card View (for very small screens) */
        @media screen and (max-width: 640px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }

            .table-container {
                padding: 10px;
                background: transparent;
                box-shadow: none;
            }

            /* Hide table headers */
            .responsive-table thead {
                position: absolute;
                width: 1px;
                height: 1px;
                padding: 0;
                margin: -1px;
                overflow: hidden;
                clip: rect(0,0,0,0);
                border: 0;
            }

            /* Convert rows to cards */
            .responsive-table tbody tr {
                display: block;
                background: white;
                margin-bottom: 15px;
                padding: 15px;
                border-radius: 12px;
                box-shadow: 0 2px 10px rgba(0,0,0,0.05);
                border: 1px solid #eee;
            }

            .responsive-table td {
                display: flex;
                align-items: center;
                padding: 10px 0;
                border: none;
                border-bottom: 1px solid #eee;
            }

            .responsive-table td:last-child {
                border-bottom: none;
            }

            /* Add labels for each cell */
            .responsive-table td::before {
                content: attr(data-label);
                font-weight: 600;
                width: 100px;
                min-width: 100px;
                color: #666;
                font-size: 0.85rem;
            }

            /* Special styling for message preview */
            .message-preview {
                max-width: none;
                white-space: normal;
                word-break: break-word;
            }
        }

        /* Tablet Styles */
        @media screen and (min-width: 641px) and (max-width: 1024px) {
            .main-content {
                padding: 20px;
            }

            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .responsive-table {
                font-size: 0.85rem;
            }

            .responsive-table th,
            .responsive-table td {
                padding: 10px 8px;
            }
        }

        /* Desktop Styles */
        @media screen and (min-width: 1025px) {
            .mobile-nav {
                display: none;
            }

            .container {
                flex-direction: row;
            }

            .sidebar {
                position: relative;
                left: 0;
                width: 260px;
                height: 100vh;
            }

            .main-content {
                margin-left: 0;
                padding: 30px;
            }

            .stats-grid {
                grid-template-columns: repeat(4, 1fr);
            }
        }

        /* Message Preview */
        .message-preview {
            max-width: 250px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            color: #666;
        }

        @media screen and (max-width: 640px) {
            .message-preview {
                max-width: none;
                white-space: normal;
            }
        }

        /* Topic Badge */
        .topic-badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.8rem;
            font-weight: 500;
            background: #e3f2fd;
            color: #1976d2;
        }

        /* Email and Phone Links */
        .contact-link {
            color: #667eea;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .contact-link:hover {
            text-decoration: underline;
        }

        /* Date Style */
        .date-cell {
            display: flex;
            align-items: center;
            gap: 5px;
            color: #666;
            font-size: 0.85rem;
            white-space: nowrap;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 40px !important;
            color: #666;
        }

        .empty-state i {
            font-size: 3rem;
            margin-bottom: 15px;
            opacity: 0.5;
        }

        /* Loading State */
        .loading {
            text-align: center;
            padding: 40px;
            color: #666;
        }

        .loading i {
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <!-- Mobile Navigation -->
    <div class="mobile-nav">
        <button class="menu-toggle" onclick="toggleMenu()">
            <i class="fas fa-bars"></i>
        </button>
        <div class="mobile-title">
            <i class="fas fa-envelope"></i>
            <span>Messages</span>
        </div>
        <div class="mobile-user">
            <i class="fas fa-user"></i>
        </div>
    </div>

    <!-- Overlay -->
    <div class="overlay" onclick="toggleMenu()"></div>

    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <h3>
                <i class="fas fa-shield-alt"></i>
                Admin Panel
            </h3>
            <p>
                <i class="fas fa-user-circle"></i>
                <?php echo htmlspecialchars($_SESSION['admin']); ?>
            </p>
        </div>
        
        <ul class="sidebar-menu">
            <li>
                <a href="../admin/dashbord.php">
                    <i class="fas fa-tachometer-alt"></i>
                    Dashboard
                </a>
            </li>
            <li>
                <a href="../php/contacte.php" class="active">
                    <i class="fas fa-envelope"></i>
                    Contact Messages
                </a>
            </li>
            <li>
                <a href="../admin/lougout.php">
                    <i class="fas fa-sign-out-alt"></i>
                    Logout
                </a>
            </li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Stats Cards -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-envelope"></i>
                </div>
                <div class="stat-info">
                    <h3><?php echo mysqli_num_rows($result); ?></h3>
                    <p>Total Messages</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-envelope-open"></i>
                </div>
                <div class="stat-info">
                    <h3><?php 
                        $unread = mysqli_query($conn, "SELECT COUNT(*) as count FROM contact WHERE status='unread'");
                        $unread_count = mysqli_fetch_assoc($unread)['count'];
                        echo $unread_count;
                    ?></h3>
                    <p>Unread</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-info">
                    <h3><?php 
                        $unique = mysqli_query($conn, "SELECT COUNT(DISTINCT email) as count FROM contact");
                        $unique_count = mysqli_fetch_assoc($unique)['count'];
                        echo $unique_count;
                    ?></h3>
                    <p>Unique Contacts</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-calendar"></i>
                </div>
                <div class="stat-info">
                    <h3><?php 
                        $today = mysqli_query($conn, "SELECT COUNT(*) as count FROM contact WHERE DATE(created_at) = CURDATE()");
                        $today_count = mysqli_fetch_assoc($today)['count'];
                        echo $today_count;
                    ?></h3>
                    <p>Today</p>
                </div>
            </div>
        </div>

        <!-- Messages Table -->
        <div class="table-container">
            <table class="responsive-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Full Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Topic</th>
                        <th>Message</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(mysqli_num_rows($result) > 0): ?>
                        <?php while($row = mysqli_fetch_assoc($result)): ?>
                            <tr>
                                <td data-label="ID">#<?php echo $row['id']; ?></td>
                                <td data-label="Full Name"><strong><?php echo htmlspecialchars($row['fullname']); ?></strong></td>
                                <td data-label="Email">
                                    <a href="mailto:<?php echo htmlspecialchars($row['email']); ?>" class="contact-link">
                                        <i class="fas fa-envelope"></i>
                                        <?php echo htmlspecialchars($row['email']); ?>
                                    </a>
                                </td>
                                <td data-label="Phone">
                                    <?php if(!empty($row['phone'])): ?>
                                        <a href="tel:<?php echo htmlspecialchars($row['phone']); ?>" class="contact-link">
                                            <i class="fas fa-phone"></i>
                                            <?php echo htmlspecialchars($row['phone']); ?>
                                        </a>
                                    <?php else: ?>
                                        -
                                    <?php endif; ?>
                                </td>
                                <td data-label="Topic">
                                    <span class="topic-badge">
                                        <?php echo htmlspecialchars($row['topic']); ?>
                                    </span>
                                </td>
                                <td data-label="Message">
                                    <div class="message-preview" title="<?php echo htmlspecialchars($row['message']); ?>">
                                        <?php echo htmlspecialchars($row['message']); ?>
                                    </div>
                                </td>
                                <td data-label="Date" class="date-cell">
                                    <i class="far fa-calendar-alt"></i>
                                    <?php echo date('M d, Y', strtotime($row['created_at'])); ?>
                                    <br>
                                    <small><?php echo date('h:i A', strtotime($row['created_at'])); ?></small>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="empty-state">
                                <i class="fas fa-inbox"></i>
                                <p>No messages found</p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        // Toggle mobile menu
        function toggleMenu() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.querySelector('.overlay');
            sidebar.classList.toggle('active');
            overlay.classList.toggle('active');
            
            // Prevent body scroll when menu is open
            if(sidebar.classList.contains('active')) {
                document.body.style.overflow = 'hidden';
            } else {
                document.body.style.overflow = 'auto';
            }
        }

        // Close menu on resize if in desktop mode
        window.addEventListener('resize', function() {
            if(window.innerWidth > 1024) {
                const sidebar = document.getElementById('sidebar');
                const overlay = document.querySelector('.overlay');
                sidebar.classList.remove('active');
                overlay.classList.remove('active');
                document.body.style.overflow = 'auto';
            }
        });

        // Touch swipe to close menu (for mobile)
        let touchStartX = 0;
        let touchEndX = 0;

        document.addEventListener('touchstart', function(event) {
            touchStartX = event.changedTouches[0].screenX;
        }, false);

        document.addEventListener('touchend', function(event) {
            touchEndX = event.changedTouches[0].screenX;
            handleSwipe();
        }, false);

        function handleSwipe() {
            const sidebar = document.getElementById('sidebar');
            const swipeThreshold = 100;
            
            // Swipe left to close
            if(touchStartX - touchEndX > swipeThreshold && sidebar.classList.contains('active')) {
                toggleMenu();
            }
            
            // Swipe right to open
            if(touchEndX - touchStartX > swipeThreshold && !sidebar.classList.contains('active') && touchStartX < 50) {
                toggleMenu();
            }
        }

        // Add loading state if needed
        document.addEventListener('DOMContentLoaded', function() {
            const tableBody = document.querySelector('tbody');
            if(tableBody.children.length === 0) {
                tableBody.innerHTML = `
                    <tr>
                        <td colspan="7" class="loading">
                            <i class="fas fa-spinner"></i> Loading...
                        </td>
                    </tr>
                `;
            }
        });
    </script>
</body>
</html>