<?php
session_start();
include("db.php");


if(!isset($_SESSION['admin'])){
    header("Location: adminlogin.php");
    exit();
}

// Handle add admin form submission
$add_admin_message = '';
$add_admin_error = '';
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_admin'])) {
    $new_admin_username = $conn->real_escape_string($_POST['admin_username']);
    $new_admin_password = password_hash($_POST['admin_password'], PASSWORD_DEFAULT);
    
    $check_admin = $conn->query("SELECT * FROM admins WHERE username = '$new_admin_username'");
    if($check_admin->num_rows > 0) {
        $add_admin_error = "Admin username already exists!";
    } else {
        $insert_admin = $conn->query("INSERT INTO admins (username, password, created_at) VALUES ('$new_admin_username', '$new_admin_password', NOW())");
        if($insert_admin) {
            $add_admin_message = "New admin added successfully!";
        } else {
            $add_admin_error = "Error adding admin: " . $conn->error;
        }
    }
}

// Handle announcement submission
$announcement_message = '';
$announcement_error = '';
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_announcement'])) {
    $title = $conn->real_escape_string($_POST['announcement_title']);
    $content = $conn->real_escape_string($_POST['announcement_content']);
    $priority = $conn->real_escape_string($_POST['priority']);
    
    if(empty($title) || empty($content)) {
        $announcement_error = "Title and content are required!";
    } else {
        $insert_announcement = $conn->query("INSERT INTO announcements (title, content, priority, created_by, created_at) VALUES ('$title', '$content', '$priority', '{$_SESSION['admin']}', NOW())");
        if($insert_announcement) {
            $announcement_message = "Announcement posted successfully!";
        } else {
            $announcement_error = "Error posting announcement: " . $conn->error;
        }
    }
}

// Get statistics with trends
$total_volunteers = $conn->query("SELECT COUNT(*) as count FROM  volunteers")->fetch_assoc()['count'];
$total_support = $conn->query("SELECT COUNT(*) as count FROM support_requests")->fetch_assoc()['count'];
$total_admins = $conn->query("SELECT COUNT(*) as count FROM admins")->fetch_assoc()['count'];

// Get last month's stats for trends
$last_month_volunteers = $conn->query("
    SELECT COUNT(*) as count FROM volunteers 
    WHERE joined_at BETWEEN DATE_SUB(NOW(), INTERVAL 2 MONTH) 
    AND DATE_SUB(NOW(), INTERVAL 1 MONTH)
")->fetch_assoc()['count'];

$last_month_support = $conn->query("
    SELECT COUNT(*) as count FROM support_requests 
    WHERE submitted_at BETWEEN DATE_SUB(NOW(), INTERVAL 2 MONTH) 
    AND DATE_SUB(NOW(), INTERVAL 1 MONTH)
")->fetch_assoc()['count'];

// Calculate trends
$volunteer_trend = $total_volunteers - $last_month_volunteers;
$volunteer_percentage = $last_month_volunteers > 0 ? round(($volunteer_trend / $last_month_volunteers) * 100) : ($total_volunteers > 0 ? 100 : 0);
$support_trend = $total_support - $last_month_support;
$support_percentage = $last_month_support > 0 ? round(($support_trend / $last_month_support) * 100) : ($total_support > 0 ? 100 : 0);

// Get pending actions for notifications
$pending_support = $conn->query("
    SELECT COUNT(*) as count FROM support_requests 
    WHERE status = 'pending' OR status IS NULL
")->fetch_assoc()['count'];

$new_volunteers_today = $conn->query("
    SELECT COUNT(*) as count FROM volunteers 
    WHERE DATE(joined_at) = CURDATE()
")->fetch_assoc()['count'];

$total_notifications = $pending_support + $new_volunteers_today;

// Get announcements
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

// Get recent volunteers and support
$recent_volunteers = $conn->query("SELECT * FROM volunteers ORDER BY joined_at DESC LIMIT 5");
$recent_support = $conn->query("SELECT * FROM support_requests ORDER BY submitted_at DESC LIMIT 5");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome <?= $_SESSION['admin']; ?> - Admin Panel</title>
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

        /* Welcome Header with Notification Bell */
        .welcome-header {
            background: white;
            border-radius: 20px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            position: relative;
            overflow: hidden;
            display: flex;
            justify-content: space-between;
            align-items: center;
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

        .welcome-text {
            position: relative;
        }

        .welcome-text h1 {
            color: #1a1a2e;
            font-size: 2.5em;
            margin-bottom: 10px;
            font-weight: 400;
        }

        .welcome-text h1 span {
            color: #667eea;
            font-weight: 600;
        }

        .welcome-text p {
            color: #4a5568;
            font-size: 1.2em;
            line-height: 1.6;
        }

        /* Notification Bell */
        .notification-bell {
            position: relative;
            cursor: pointer;
            font-size: 1.8em;
            color: #667eea;
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
            animation: pulse 1s infinite;
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); }
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
            animation: slideDown 0.3s ease;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .notification-header {
            padding: 15px 20px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            border-radius: 15px 15px 0 0;
            font-weight: 500;
        }

        .notification-list {
            max-height: 400px;
            overflow-y: auto;
        }

        .notification-item {
            padding: 15px 20px;
            border-bottom: 1px solid #eef2f6;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .notification-item:hover {
            background: #f8faff;
        }

        .notification-item.unread {
            background: #f0f4ff;
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

        .notification-time {
            color: #a0aec0;
            font-size: 0.7em;
            margin-top: 5px;
        }

        /* Search Section */
        .search-section {
            margin-bottom: 30px;
        }

        .search-box {
            background: white;
            border-radius: 50px;
            padding: 15px 25px;
            display: flex;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }

        .search-box:focus-within {
            box-shadow: 0 4px 20px rgba(102, 126, 234, 0.2);
            transform: translateY(-2px);
        }

        .search-box i {
            color: #667eea;
            font-size: 1.2em;
            margin-right: 15px;
        }

        .search-box input {
            flex: 1;
            border: none;
            outline: none;
            font-size: 1em;
            background: transparent;
        }

        .filter-buttons {
            display: flex;
            gap: 10px;
            margin-top: 15px;
            flex-wrap: wrap;
        }

        .filter-btn {
            padding: 8px 20px;
            border: none;
            border-radius: 25px;
            background: white;
            color: #4a5568;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .filter-btn.active {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
        }

        .filter-btn:hover:not(.active) {
            background: #eef2f6;
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
            flex: 1;
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

        .trend {
            font-size: 0.85em;
            margin-top: 5px;
        }

        .trend.up {
            color: #4caf50;
        }

        .trend.down {
            color: #f44336;
        }

        .stat-info .small-text {
            color: #718096;
            font-size: 0.9em;
            margin-top: 5px;
        }

        /* Add Admin Card */
        .add-admin-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            cursor: pointer;
        }
        
        .add-admin-card .stat-icon {
            background: rgba(255, 255, 255, 0.2);
        }
        
        .add-admin-card .stat-info h3,
        .add-admin-card .stat-info .number,
        .add-admin-card .stat-info .small-text {
            color: white;
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
            font-weight: 500;
        }

        .announcement-header h2 i {
            margin-right: 10px;
            color: #667eea;
        }

        .add-announcement-btn {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            border: none;
            padding: 8px 20px;
            border-radius: 25px;
            cursor: pointer;
            transition: all 0.3s ease;
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
            border-left: 4px solid #667eea;
            transition: all 0.3s ease;
        }

        .announcement-item:hover {
            transform: translateX(5px);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
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
            align-items: center;
        }

        .priority-badge {
            font-size: 0.7em;
            padding: 2px 8px;
            border-radius: 20px;
            background: #eef2f6;
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

        /* Modal for Announcement */
        .announcement-modal {
            display: none;
            position: fixed;
            z-index: 2000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
        }

        .announcement-modal-content {
            background: white;
            margin: 5% auto;
            width: 90%;
            max-width: 600px;
            border-radius: 20px;
            animation: slideUp 0.3s ease;
        }

        .announcement-modal-header {
            background: linear-gradient(135deg, #667eea, #764ba2);
            padding: 20px 25px;
            border-radius: 20px 20px 0 0;
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .announcement-modal-body {
            padding: 25px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #1a1a2e;
            font-weight: 500;
        }

        .form-group input, .form-group textarea, .form-group select {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #eef2f6;
            border-radius: 10px;
            font-size: 1em;
            font-family: inherit;
        }

        .form-group textarea {
            resize: vertical;
            min-height: 100px;
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

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 2000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
        }
        
        .modal-content {
            background: white;
            margin: 5% auto;
            width: 90%;
            max-width: 500px;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            animation: slideUp 0.3s ease;
        }
        
        @keyframes slideUp {
            from {
                transform: translateY(50px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }
        
        .modal-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 20px 25px;
            border-radius: 20px 20px 0 0;
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .close-modal {
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .close-modal:hover {
            transform: rotate(90deg);
        }
        
        .modal-body {
            padding: 25px;
        }
        
        .success-message, .error-message-show {
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: none;
        }
        
        .success-message {
            background: #4caf50;
            color: white;
        }
        
        .error-message-show {
            background: #f44336;
            color: white;
        }
        
        .modal-buttons {
            display: flex;
            gap: 15px;
            margin-top: 25px;
        }
        
        .btn-submit {
            flex: 1;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 12px;
            border-radius: 10px;
            cursor: pointer;
        }
        
        .btn-cancel {
            flex: 1;
            background: #eef2f6;
            color: #4a5568;
            border: none;
            padding: 12px;
            border-radius: 10px;
            cursor: pointer;
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
            .notification-panel {
                right: 20px;
                width: calc(100% - 40px);
            }
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
                <li><a href="../php/contacte.php"><i class="fa-solid fa-bullhorn"></i>Show contacted people</a></li>
                <li><a href="../admin/admin.php"><i class="fas fa-user-shield"></i> Manage Admins</a></li>
                <li><a href="../admin/lougout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </nav>
    </div>

    <div class="main-content">
        <div class="welcome-header">
            <div class="welcome-text">
                <h1>Welcome back, <span><?php echo $_SESSION['admin']; ?></span>!</h1>
                <p>Here's what's happening with your platform today.</p>
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
                <?php if($pending_support > 0): ?>
                <div class="notification-item unread">
                    <div class="notification-title">Pending Support Requests</div>
                    <div class="notification-message">You have <?php echo $pending_support; ?> pending support request(s) that need attention.</div>
                    <div class="notification-time">Just now</div>
                </div>
                <?php endif; ?>
                <?php if($new_volunteers_today > 0): ?>
                <div class="notification-item unread">
                    <div class="notification-title">New Volunteers Today</div>
                    <div class="notification-message"><?php echo $new_volunteers_today; ?> new volunteer(s) joined today!</div>
                    <div class="notification-time">Today</div>
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

        <!-- Search & Filter Section -->
        <div class="search-section">
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" id="globalSearch" placeholder="Search volunteers, support requests...">
            </div>
            <div class="filter-buttons">
                <button class="filter-btn active" data-filter="all">All</button>
                <button class="filter-btn" data-filter="volunteers">Volunteers</button>
                <button class="filter-btn" data-filter="support">Support Requests</button>
            </div>
        </div>

        <!-- Stats Cards with Trends -->
        <div class="stats-container">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-info">
                    <h3>Total Volunteers</h3>
                    <div class="number"><?php echo $total_volunteers; ?></div>
                    <div class="trend <?php echo $volunteer_trend >= 0 ? 'up' : 'down'; ?>">
                        <i class="fas fa-arrow-<?php echo $volunteer_trend >= 0 ? 'up' : 'down'; ?>"></i>
                        <?php echo abs($volunteer_percentage); ?>% from last month
                    </div>
                    <div class="small-text">Active members</div>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-hand-holding-heart"></i>
                </div>
                <div class="stat-info">
                    <h3>Support Requests</h3>
                    <div class="number"><?php echo $total_support; ?></div>
                    <div class="trend <?php echo $support_trend >= 0 ? 'up' : 'down'; ?>">
                        <i class="fas fa-arrow-<?php echo $support_trend >= 0 ? 'up' : 'down'; ?>"></i>
                        <?php echo abs($support_percentage); ?>% from last month
                    </div>
                    <div class="small-text">People need help</div>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-user-shield"></i>
                </div>
                <div class="stat-info">
                    <h3>Total Admins</h3>
                    <div class="number"><?php echo $total_admins; ?></div>
                    <div class="small-text">Administrators</div>
                </div>
            </div>
            
            <div class="stat-card add-admin-card" onclick="openAddAdminModal()">
                <div class="stat-icon">
                    <i class="fas fa-plus-circle"></i>
                </div>
                <div class="stat-info">
                    <h3>Add New Admin</h3>
                    <div class="number">+</div>
                    <div class="small-text">Click to add</div>
                </div>
            </div>
        </div>

        <!-- Announcement System -->
        <div class="announcement-card">
            <div class="announcement-header">
                <h2><i class="fas fa-bullhorn"></i> Announcements</h2>
                <button class="add-announcement-btn" onclick="openAnnouncementModal()">
                    <i class="fas fa-plus"></i> Post Announcement
                </button>
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
                            <i class="fas fa-user"></i> <?php echo htmlspecialchars($ann['created_by']); ?> &nbsp;
                            <i class="fas fa-calendar"></i> <?php echo date('M d, Y', strtotime($ann['created_at'])); ?>
                        </div>
                    </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="announcement-item">
                        <div class="announcement-title">No Announcements</div>
                        <div class="announcement-content">Click the button above to post your first announcement.</div>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="recent-activity">
            <div class="activity-card" id="volunteersList">
                <div class="activity-header">
                    <h2><i class="fas fa-user-plus"></i> Recent Volunteers</h2>
                    <a href="../admin/volunteerjoined.php" class="view-all">View All <i class="fas fa-arrow-right"></i></a>
                </div>
                <ul class="activity-list">
                    <?php while($vol = $recent_volunteers->fetch_assoc()): ?>
                    <li class="activity-item" data-type="volunteers">
                        <div class="activity-avatar">
                            <?php echo strtoupper(substr($vol['full_name'], 0, 2)); ?>
                        </div>
                        <div class="activity-details">
                            <h4><?php echo htmlspecialchars($vol['full_name']); ?></h4>
                            <p><?php echo htmlspecialchars($vol['role']); ?></p>
                        </div>
                        <div class="activity-time">
                            <?php echo date('M d', strtotime($vol['joined_at'])); ?>
                        </div>
                    </li>
                    <?php endwhile; ?>
                </ul>
            </div>

            <div class="activity-card" id="supportList">
                <div class="activity-header">
                    <h2><i class="fas fa-hand-holding-heart"></i> Recent Support</h2>
                    <a href="../php/supportre.php" class="view-all">View All <i class="fas fa-arrow-right"></i></a>
                </div>
                <ul class="activity-list">
                    <?php 
                    if($recent_support && $recent_support->num_rows > 0):
                        while($support = $recent_support->fetch_assoc()): ?>
                        <li class="activity-item" data-type="support">
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
                        <li class="activity-item" data-type="support">
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
            <a href="./volunteerjoined.php" class="action-btn">
                <i class="fas fa-user-plus"></i>
                <span>View Volunteers</span>
            </a>
            <a href="../php/supportre.php" class="action-btn">
                <i class="fas fa-hand-holding-heart"></i>
                <span>Check Support</span>
            </a>
        </div>
    </div>

    <!-- Add Admin Modal -->
    <div id="addAdminModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2><i class="fas fa-user-plus"></i> Add New Administrator</h2>
                <span class="close-modal" onclick="closeAddAdminModal()">&times;</span>
            </div>
            <div class="modal-body">
                <div id="modalSuccessMessage" class="success-message">
                    <i class="fas fa-check-circle"></i> <span id="successText"></span>
                </div>
                <div id="modalErrorMessage" class="error-message-show">
                    <i class="fas fa-exclamation-circle"></i> <span id="errorText"></span>
                </div>
                
                <form id="addAdminForm" method="POST" action="">
                    <div class="form-group">
                        <label>Username *</label>
                        <input type="text" name="admin_username" required placeholder="Enter username">
                    </div>
                    
                    <div class="form-group">
                        <label>Password *</label>
                        <input type="password" name="admin_password" required placeholder="Enter password">
                    </div>
                    
                    <div class="form-group">
                        <label>Confirm Password *</label>
                        <input type="password" name="confirm_password" required placeholder="Confirm password">
                    </div>
                    
                    <div class="modal-buttons">
                        <button type="submit" name="add_admin" class="btn-submit">Add Admin</button>
                        <button type="button" class="btn-cancel" onclick="closeAddAdminModal()">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Announcement Modal -->
    <div id="announcementModal" class="announcement-modal">
        <div class="announcement-modal-content">
            <div class="announcement-modal-header">
                <h2><i class="fas fa-bullhorn"></i> Post New Announcement</h2>
                <span class="close-modal" onclick="closeAnnouncementModal()">&times;</span>
            </div>
            <div class="announcement-modal-body">
                <div id="announcementSuccess" class="success-message"></div>
                <div id="announcementError" class="error-message-show"></div>
                
                <form method="POST" action="">
                    <div class="form-group">
                        <label>Title *</label>
                        <input type="text" name="announcement_title" required placeholder="Enter announcement title">
                    </div>
                    
                    <div class="form-group">
                        <label>Content *</label>
                        <textarea name="announcement_content" required placeholder="Write your announcement here..."></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label>Priority</label>
                        <select name="priority">
                            <option value="low">Low</option>
                            <option value="medium" selected>Medium</option>
                            <option value="high">High</option>
                        </select>
                    </div>
                    
                    <div class="modal-buttons">
                        <button type="submit" name="add_announcement" class="btn-submit">Post Announcement</button>
                        <button type="button" class="btn-cancel" onclick="closeAnnouncementModal()">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Notification Panel Toggle
        function toggleNotifications() {
            const panel = document.getElementById('notificationPanel');
            panel.style.display = panel.style.display === 'block' ? 'none' : 'block';
        }

        // Close notification panel when clicking outside
        document.addEventListener('click', function(event) {
            const panel = document.getElementById('notificationPanel');
            const bell = document.querySelector('.notification-bell');
            if (!bell.contains(event.target) && !panel.contains(event.target)) {
                panel.style.display = 'none';
            }
        });

        // Search and Filter Functionality
        const searchInput = document.getElementById('globalSearch');
        const filterBtns = document.querySelectorAll('.filter-btn');
        let currentFilter = 'all';

        function filterItems() {
            const searchTerm = searchInput.value.toLowerCase();
            const allItems = document.querySelectorAll('.activity-item');
            
            allItems.forEach(item => {
                const text = item.innerText.toLowerCase();
                const type = item.getAttribute('data-type');
                const matchesSearch = text.includes(searchTerm);
                const matchesFilter = currentFilter === 'all' || type === currentFilter;
                
                if (matchesSearch && matchesFilter) {
                    item.style.display = 'flex';
                } else {
                    item.style.display = 'none';
                }
            });
        }

        searchInput.addEventListener('keyup', filterItems);

        filterBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                filterBtns.forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                currentFilter = this.getAttribute('data-filter');
                filterItems();
            });
        });

        // Modal functions
        function openAddAdminModal() {
            document.getElementById('addAdminModal').style.display = 'block';
        }
        
        function closeAddAdminModal() {
            document.getElementById('addAdminModal').style.display = 'none';
        }

        function openAnnouncementModal() {
            document.getElementById('announcementModal').style.display = 'block';
        }
        
        function closeAnnouncementModal() {
            document.getElementById('announcementModal').style.display = 'none';
        }
        
        window.onclick = function(event) {
            if (event.target.classList.contains('modal')) {
                event.target.style.display = 'none';
            }
            if (event.target.classList.contains('announcement-modal')) {
                event.target.style.display = 'none';
            }
        }

        // Show messages
        <?php if($add_admin_message): ?>
        document.addEventListener('DOMContentLoaded', function() {
            const msgDiv = document.getElementById('modalSuccessMessage');
            document.getElementById('successText').innerHTML = '<?php echo $add_admin_message; ?>';
            msgDiv.style.display = 'block';
            setTimeout(() => {
                msgDiv.style.display = 'none';
                closeAddAdminModal();
                location.reload();
            }, 2000);
        });
        <?php endif; ?>
        
        <?php if($add_admin_error): ?>
        document.addEventListener('DOMContentLoaded', function() {
            const msgDiv = document.getElementById('modalErrorMessage');
            document.getElementById('errorText').innerHTML = '<?php echo $add_admin_error; ?>';
            msgDiv.style.display = 'block';
            setTimeout(() => msgDiv.style.display = 'none', 3000);
        });
        <?php endif; ?>

        <?php if($announcement_message): ?>
        document.addEventListener('DOMContentLoaded', function() {
            const msgDiv = document.getElementById('announcementSuccess');
            msgDiv.innerHTML = '<i class="fas fa-check-circle"></i> <?php echo $announcement_message; ?>';
            msgDiv.style.display = 'block';
            setTimeout(() => {
                msgDiv.style.display = 'none';
                closeAnnouncementModal();
                location.reload();
            }, 2000);
        });
        <?php endif; ?>
        
        <?php if($announcement_error): ?>
        document.addEventListener('DOMContentLoaded', function() {
            const msgDiv = document.getElementById('announcementError');
            msgDiv.innerHTML = '<i class="fas fa-exclamation-circle"></i> <?php echo $announcement_error; ?>';
            msgDiv.style.display = 'block';
            setTimeout(() => msgDiv.style.display = 'none', 3000);
        });
        <?php endif; ?>
    </script>
</body>
</html>