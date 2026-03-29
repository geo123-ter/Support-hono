<?php
session_start();
include('db.php');

// Check if user is logged in
if(!isset($_SESSION['admin'])){
    header("Location: adminlogin.php");
    exit();
}

// Handle delete admin
if(isset($_GET['delete'])) {
    $delete_id = $conn->real_escape_string($_GET['delete']);
    // Prevent deleting own account
    if($delete_id != $_SESSION['admin_id']) {
        $conn->query("DELETE FROM admins WHERE id = $delete_id");
        $success_message = "Admin deleted successfully!";
    } else {
        $error_message = "You cannot delete your own account!";
    }
    header("Location: manage_admins.php");
    exit();
}

// Fetch all admins
$sql = "SELECT * FROM admins ORDER BY created_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Admins - Admin Panel</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
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
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
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

        nav {
            flex: 1;
            padding: 20px 0;
            overflow-y: auto;
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

        /* Main Content */
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

        /* Page Header */
        .page-header {
            background: white;
            border-radius: 20px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            position: relative;
            overflow: hidden;
        }

        .page-header::before {
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

        .page-header h1 {
            color: #1a1a2e;
            font-size: 2em;
            margin-bottom: 10px;
            font-weight: 500;
        }

        .page-header h1 i {
            color: #667eea;
            margin-right: 10px;
        }

        .page-header p {
            color: #4a5568;
            font-size: 1.1em;
        }

        /* Stats Bar */
        .stats-bar {
            display: flex;
            gap: 20px;
            margin-bottom: 30px;
            flex-wrap: wrap;
        }

        .stat-box {
            background: white;
            padding: 20px 30px;
            border-radius: 15px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            display: flex;
            align-items: center;
            gap: 15px;
            flex: 1;
            transition: all 0.3s ease;
        }

        .stat-box:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        }

        .stat-box-icon {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .stat-box-icon i {
            color: white;
            font-size: 24px;
        }

        .stat-box-info h3 {
            color: #4a5568;
            font-size: 0.9em;
            margin-bottom: 5px;
        }

        .stat-box-info .number {
            color: #1a1a2e;
            font-size: 2em;
            font-weight: 600;
        }

        /* Admin Table */
        .admin-table-container {
            background: white;
            border-radius: 20px;
            padding: 25px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            overflow-x: auto;
        }

        .table-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #eef2f6;
        }

        .table-header h2 {
            color: #1a1a2e;
            font-size: 1.3em;
            font-weight: 500;
        }

        .table-header h2 i {
            color: #667eea;
            margin-right: 10px;
        }

        .add-admin-btn {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .add-admin-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead {
            background: #f8f9fa;
        }

        th {
            padding: 15px;
            text-align: left;
            color: #4a5568;
            font-weight: 600;
            font-size: 0.9em;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        td {
            padding: 15px;
            border-bottom: 1px solid #eef2f6;
            color: #1a1a2e;
        }

        tr {
            transition: all 0.3s ease;
        }

        tr:hover {
            background: #f8faff;
            transform: translateX(5px);
        }

        /* Admin Avatar */
        .admin-avatar {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
        }

        .admin-info {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .admin-details h4 {
            font-size: 1em;
            margin-bottom: 3px;
        }

        .admin-details p {
            font-size: 0.8em;
            color: #718096;
        }

        /* Badge */
        .badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.8em;
            font-weight: 500;
        }

        .badge-primary {
            background: #667eea;
            color: white;
        }

        .badge-success {
            background: #48bb78;
            color: white;
        }

        /* Action Buttons */
        .action-buttons {
            display: flex;
            gap: 10px;
        }

        .btn-icon {
            width: 35px;
            height: 35px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            transition: all 0.3s ease;
            cursor: pointer;
            border: none;
        }

        .btn-view {
            background: #eef2f6;
            color: #667eea;
        }

        .btn-view:hover {
            background: #667eea;
            color: white;
            transform: translateY(-2px);
        }

        .btn-delete {
            background: #fee2e2;
            color: #f44336;
        }

        .btn-delete:hover {
            background: #f44336;
            color: white;
            transform: translateY(-2px);
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
            animation: fadeIn 0.3s ease;
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

        .modal-header h2 {
            margin: 0;
            font-size: 1.5em;
            font-weight: 500;
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

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #1a1a2e;
            font-weight: 500;
        }

        .form-group input {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #eef2f6;
            border-radius: 10px;
            font-size: 1em;
            transition: all 0.3s ease;
        }

        .form-group input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
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
            font-size: 1em;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }

        .btn-cancel {
            flex: 1;
            background: #eef2f6;
            color: #4a5568;
            border: none;
            padding: 12px;
            border-radius: 10px;
            font-size: 1em;
            font-weight: 500;
            cursor: pointer;
        }

        .btn-cancel:hover {
            background: #e2e8f0;
        }

        /* Alert Messages */
        .alert {
            padding: 15px 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            animation: slideIn 0.3s ease;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border-left: 4px solid #28a745;
        }

        .alert-danger {
            background: #f8d7da;
            color: #721c24;
            border-left: 4px solid #dc3545;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
        }

        .empty-state i {
            font-size: 4em;
            color: #cbd5e0;
            margin-bottom: 20px;
        }

        .empty-state h3 {
            color: #4a5568;
            margin-bottom: 10px;
        }

        .empty-state p {
            color: #718096;
        }

        /* Responsive */
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
            
            .stats-bar {
                flex-direction: column;
            }
            
            .action-buttons {
                flex-direction: column;
                gap: 5px;
            }
            
            .btn-icon {
                width: 30px;
                height: 30px;
            }
            
            th, td {
                padding: 10px;
            }
        }
        span {
            color: blue;
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
                <li><a href="../admin/dashbord.php"><i class="fas fa-home"></i> Dashboard</a></li>
                <li><a href="../admin/volunteerjoined.php"><i class="fas fa-user-plus"></i> Volunteers</a></li>
                <li><a href="../php/supportre.php"><i class="fas fa-hand-holding-heart"></i> People Need Support</a></li>
                <li><a href="../php/contacte.php"><i class="fa-solid fa-bullhorn"></i> Contacted People</a></li>
                <li><a href="../admin/admin.php" class="active"><i class="fas fa-user-shield"></i> Manage Admins</a></li>
                <li><a href="../admin/lougout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </nav>
    </div>

    <div class="main-content">
        <div class="page-header">
            <h1><i class="fas fa-user-shield"></i> Manage Administrators As <span><?php echo $_SESSION['admin']; ?></span></h1>
            <p>View and manage all administrator accounts</p>
        </div>

        <?php if(isset($_GET['success'])): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                <span><?php echo htmlspecialchars($_GET['success']); ?></span>
            </div>
        <?php endif; ?>

        <?php if(isset($_GET['error'])): ?>
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle"></i>
                <span><?php echo htmlspecialchars($_GET['error']); ?></span>
            </div>
        <?php endif; ?>

        <div class="stats-bar">
            <div class="stat-box">
                <div class="stat-box-icon">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-box-info">
                    <h3>Total Administrators</h3>
                    <div class="number"><?php echo $result->num_rows; ?></div>
                </div>
            </div>
            <div class="stat-box">
                <div class="stat-box-icon">
                    <i class="fas fa-user-check"></i>
                </div>
                <div class="stat-box-info">
                    <h3>Active Admins</h3>
                    <div class="number"><?php echo $result->num_rows; ?></div>
                </div>
            </div>
        </div>

        <div class="admin-table-container">
            <div class="table-header">
                <h2><i class="fas fa-list"></i> Administrator List</h2>
                <button class="add-admin-btn" onclick="openAddAdminModal()">
                    <i class="fas fa-plus-circle"></i> Add New Admin
                </button>
            </div>

            <?php if($result && $result->num_rows > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Admin</th>
                            <th>Username</th>
                            <th>Created Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $admins = [];
                        while ($row = $result->fetch_assoc()) {
                            $admins[] = $row;
                        }
                        foreach ($admins as $admin): 
                        ?>
                        <tr>
                            <td>
                                <div class="admin-info">
                                    <div class="admin-avatar">
                                        <?php echo strtoupper(substr($admin['username'], 0, 2)); ?>
                                    </div>
                                    <div class="admin-details">
                                        <h4><?php echo htmlspecialchars($admin['username']); ?></h4>
                                        <p>ID: #<?php echo $admin['id']; ?></p>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <strong><?php echo htmlspecialchars($admin['username']); ?></strong>
                                <?php if($admin['username'] == $_SESSION['admin']): ?>
                                    <span class="badge badge-primary" style="margin-left: 8px;">You</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <i class="fas fa-calendar-alt" style="color: #667eea; margin-right: 5px;"></i>
                                <?php echo date('M d, Y', strtotime($admin['created_at'])); ?>
                            </td>
                            <td>
                                <span class="badge badge-success">
                                    <i class="fas fa-circle" style="font-size: 8px; margin-right: 5px;"></i>
                                    Active
                                </span>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <a href="view_admin.php?id=<?php echo $admin['id']; ?>" class="btn-icon btn-view" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <?php if($admin['username'] != $_SESSION['admin']): ?>
                                        <a href="?delete=<?php echo $admin['id']; ?>" 
                                           class="btn-icon btn-delete" 
                                           title="Delete Admin"
                                           onclick="return confirm('Are you sure you want to delete this admin? This action cannot be undone.')">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="empty-state">
                    <i class="fas fa-user-shield"></i>
                    <h3>No Administrators Found</h3>
                    <p>Click the "Add New Admin" button to create your first administrator account.</p>
                </div>
            <?php endif; ?>
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
                <form id="addAdminForm" method="POST" action="add_admin_process.php">
                    <div class="form-group">
                        <label for="username">Username <span style="color: #f44336;">*</span></label>
                        <input type="text" id="username" name="username" required 
                               placeholder="Enter username" autocomplete="off">
                    </div>
                    
                    <div class="form-group">
                        <label for="password">Password <span style="color: #f44336;">*</span></label>
                        <input type="password" id="password" name="password" required 
                               placeholder="Enter password">
                        <small style="color: #718096; font-size: 0.8em;">Password must be at least 6 characters</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="confirm_password">Confirm Password <span style="color: #f44336;">*</span></label>
                        <input type="password" id="confirm_password" name="confirm_password" required 
                               placeholder="Confirm password">
                    </div>
                    
                    <div class="modal-buttons">
                        <button type="submit" name="add_admin" class="btn-submit">
                            <i class="fas fa-plus-circle"></i> Add Admin
                        </button>
                        <button type="button" class="btn-cancel" onclick="closeAddAdminModal()">
                            <i class="fas fa-times"></i> Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Modal functions
        function openAddAdminModal() {
            document.getElementById('addAdminModal').style.display = 'block';
            document.body.style.overflow = 'hidden';
        }
        
        function closeAddAdminModal() {
            document.getElementById('addAdminModal').style.display = 'none';
            document.body.style.overflow = 'auto';
        }
        
        // Close modal when clicking outside
        window.onclick = function(event) {
            let modal = document.getElementById('addAdminModal');
            if (event.target == modal) {
                closeAddAdminModal();
            }
        }
        
        // Form validation
        document.getElementById('addAdminForm').addEventListener('submit', function(e) {
            const password = document.getElementById('password').value;
            const confirm = document.getElementById('confirm_password').value;
            
            if (password !== confirm) {
                e.preventDefault();
                alert('Passwords do not match!');
                return false;
            }
            
            if (password.length < 6) {
                e.preventDefault();
                alert('Password must be at least 6 characters long!');
                return false;
            }
        });
        
        // Auto-hide alerts after 5 seconds
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 300);
            });
        }, 5000);
    </script>
</body>
</html>