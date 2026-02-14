<?php
session_start();

if(!isset($_SESSION['admin'])) {
    die("Access denied. Please log in as admin.");
}

$conn = new mysqli("localhost", "root", "", "ngo_db");

$message = '';
$messageType = '';

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $title = $_POST['title'];
    $content = $_POST['content'];
    $author = $_SESSION['admin'];
    
    if(isset($_FILES['image']) && $_FILES['image']['error'] == 0){
        $upload_dir = '../uploads/';
        
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        
        $filename = time() . '_' . basename($_FILES['image']['name']);
        $image_path = $upload_dir . $filename;

        $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $file_type = $_FILES['image']['type'];
        
        if(in_array($file_type, $allowed_types)) {
            if(move_uploaded_file($_FILES['image']['tmp_name'], $image_path)) {
                $image = 'uploads/' . $filename;
            } else {
                $image = null;
                $message = "Failed to upload image.";
                $messageType = 'error';
            }
        } else {
            $image = null;
            $message = "Invalid image type. Please use JPG, PNG, GIF, or WebP.";
            $messageType = 'error';
        }
    } else {
        $image = null;
    }

    if(empty($message)) {
        $stmt = $conn->prepare("INSERT INTO blog_posts (title, content, author, image) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $title, $content, $author, $image);
        
        if($stmt->execute()) {
            $message = "Post published successfully!";
            $messageType = 'success';
        } else {
            $message = "Error publishing post. Please try again.";
            $messageType = 'error';
        }
        $stmt->close();
    }
}

$recent_posts = $conn->query("SELECT * FROM blog_posts ORDER BY created_at DESC LIMIT 5");

$total_posts = $conn->query("SELECT COUNT(*) as count FROM blog_posts")->fetch_assoc()['count'];
$total_images = $conn->query("SELECT COUNT(*) as count FROM blog_posts WHERE image IS NOT NULL")->fetch_assoc()['count'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard | Blog Management</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        * { margin:0; padding:0; box-sizing:border-box; }

        body {
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height:100vh;
            padding:40px 20px;
        }

        .dashboard-container { max-width:1200px; margin:0 auto; }

        .dashboard-header {
            background:rgba(255,255,255,0.95);
            border-radius:20px;
            padding:30px;
            margin-bottom:30px;
            box-shadow:0 20px 40px rgba(0,0,0,0.1);
        }

        .header-content {
            display:flex;
            justify-content:space-between;
            align-items:center;
            flex-wrap:wrap;
            gap:20px;
        }

        .header-title h1 {
            font-size:2em;
            color:#333;
        }

        .admin-badge {
            background:linear-gradient(135deg,#667eea,#764ba2);
            color:white;
            padding:12px 25px;
            border-radius:50px;
            display:flex;
            align-items:center;
            gap:10px;
        }

        .stats-grid {
            display:grid;
            grid-template-columns:repeat(auto-fit,minmax(150px,1fr));
            gap:20px;
            margin-bottom:30px;
        }

        .stat-card {
            background:white;
            border-radius:15px;
            padding:20px;
            text-align:center;
            box-shadow:0 10px 20px rgba(0,0,0,0.05);
        }

        .stat-card i {
            font-size:2em;
            color:#667eea;
            margin-bottom:10px;
        }

        .stat-value {
            font-size:1.8em;
            font-weight:bold;
        }

        .dashboard-grid {
            display:grid;
            grid-template-columns:1fr 1fr;
            gap:30px;
        }

        .card {
            background:white;
            border-radius:20px;
            padding:30px;
            box-shadow:0 20px 40px rgba(0,0,0,0.1);
        }

        .card h2 {
            margin-bottom:20px;
            display:flex;
            align-items:center;
            gap:10px;
        }

        input, textarea {
            width:100%;
            padding:12px;
            border:2px solid #eee;
            border-radius:10px;
            margin-bottom:15px;
        }

        input:focus, textarea:focus {
            outline:none;
            border-color:#667eea;
        }

        .file-input-button {
            background:#f0f2f5;
            border:2px dashed #667eea;
            border-radius:10px;
            padding:20px;
            text-align:center;
            cursor:pointer;
        }

        .submit-btn {
            background:linear-gradient(135deg,#667eea,#764ba2);
            color:white;
            border:none;
            padding:15px;
            border-radius:50px;
            cursor:pointer;
            width:100%;
            font-weight:bold;
        }

        .alert { padding:15px; border-radius:10px; margin-bottom:15px; }
        .success { background:#d4edda; color:#155724; }
        .error { background:#f8d7da; color:#721c24; }

        .posts-table { width:100%; border-collapse:collapse; }
        .posts-table th, .posts-table td { padding:10px; border-bottom:1px solid #eee; }

        .logout-btn {
            border:2px solid #667eea;
            color:#667eea;
            padding:8px 20px;
            border-radius:50px;
            text-decoration:none;
            margin-top:15px;
            display:inline-block;
        }

        @media(max-width:768px){
            .dashboard-grid { grid-template-columns:1fr; }
        }
    </style>
</head>
<body>

<div class="dashboard-container">

    <div class="dashboard-header">
        <div class="header-content">
            <div class="header-title">
                <h1><i class="fa-solid fa-pen-to-square"></i> Blog Management Dashboard</h1>
                <p>Create and manage your blog posts</p>
            </div>
            <div class="admin-badge">
                <i class="fa-solid fa-user-shield"></i>
                <?php echo htmlspecialchars($_SESSION['admin']); ?>
            </div>
        </div>
    </div>

    <div class="stats-grid">
        <div class="stat-card">
            <i class="fa-solid fa-chart-column"></i>
            <div class="stat-value"><?php echo $total_posts; ?></div>
            <div>Total Posts</div>
        </div>
        <div class="stat-card">
            <i class="fa-solid fa-image"></i>
            <div class="stat-value"><?php echo $total_images; ?></div>
            <div>With Images</div>
        </div>
        <div class="stat-card">
            <i class="fa-solid fa-pen"></i>
            <div class="stat-value"><?php echo $recent_posts->num_rows; ?></div>
            <div>Recent Posts</div>
        </div>
    </div>

    <div class="dashboard-grid">

        <div class="card">
            <h2><i class="fa-solid fa-feather"></i> Create New Post</h2>

            <?php if($message): ?>
                <div class="alert <?php echo $messageType; ?>">
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>

            <form method="post" enctype="multipart/form-data" id="blogForm">
                <input type="text" name="title" placeholder="Post Title" required maxlength="200">
                <textarea name="content" placeholder="Post Content" required></textarea>

                <div class="file-input-button" onclick="document.getElementById('image').click();">
                    <i class="fa-solid fa-camera"></i> Click to upload image
                </div>
                <input type="file" id="image" name="image" style="display:none;">
                <div id="file-name"></div>

                <button type="submit" class="submit-btn">
                    <i class="fa-solid fa-paper-plane"></i> Publish Post
                </button>
            </form>
        </div>

        <div class="card">
            <h2><i class="fa-solid fa-clock"></i> Recent Posts</h2>

            <?php if($recent_posts->num_rows > 0): ?>
                <table class="posts-table">
                    <tr>
                        <th>Title</th>
                        <th>Date</th>
                    </tr>
                    <?php while($post = $recent_posts->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars(substr($post['title'],0,30)); ?></td>
                            <td><?php echo date('M d, Y', strtotime($post['created_at'])); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </table>
            <?php else: ?>
                <p>No posts yet.</p>
            <?php endif; ?>

            <a href="blog.php" class="logout-btn"><i class="fa-solid fa-eye"></i> View Blog</a>
            <a href="logout.php" class="logout-btn"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
        </div>

    </div>
</div>

<script>
document.getElementById('image').addEventListener('change', function() {
    const fileName = this.files[0] ? this.files[0].name : '';
    document.getElementById('file-name').innerHTML =
        '<i class="fa-solid fa-paperclip"></i> ' + fileName;
});
</script>

</body>
</html>
