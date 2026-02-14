<?php
$conn = new mysqli("localhost", "root", "", "ngo_db");
$result = $conn->query("SELECT * FROM blog_posts ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Our Blog | NGO Name</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
            padding: 40px 20px;
        }

        .container {
            width: 100%;
            max-width: 1000px;
            margin: 0 auto;
        }

        /* Header Section */
        .blog-header {
            text-align: center;
            margin-bottom: 50px;
            color: white;
        }

        .blog-header h1 {
            font-size: 3em;
            margin-bottom: 10px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
            animation: fadeInDown 1s ease;
        }

        .blog-header p {
            font-size: 1.2em;
            opacity: 0.9;
            animation: fadeInUp 1s ease;
        }

        /* Blog Posts Grid */
        .blog-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
        }

        .blog-post {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            animation: fadeIn 1s ease;
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .blog-post:hover {
            transform: translateY(-10px);
            box-shadow: 0 30px 60px rgba(0,0,0,0.2);
        }

        /* Image Container */
        .post-image {
            width: 100%;
            height: 330px;
            overflow: hidden;
            position: relative;
            
        }

        .post-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .blog-post:hover .post-image img {
            transform: scale(1.1);
        }

        .no-image {
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.2em;
        }

        /* Content Area */
        .post-content {
            padding: 25px;
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .post-content h2 {
            color: #333;
            font-size: 1.5em;
            margin-bottom: 15px;
            line-height: 1.4;
            border-left: 4px solid #667eea;
            padding-left: 15px;
        }

        .post-content p {
            color: #666;
            line-height: 1.8;
            margin-bottom: 20px;
            flex: 1;
        }

        /* Author Info */
        .author-info {
            display: flex;
            align-items: center;
            margin: 15px 0;
            padding: 15px 0;
            border-top: 1px solid #eee;
            border-bottom: 1px solid #eee;
        }

        .author-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            margin-right: 12px;
            text-transform: uppercase;
        }

        .author-details {
            flex: 1;
        }

        .author-name {
            font-weight: bold;
            color: #333;
            margin-bottom: 4px;
        }

        .post-date {
            font-size: 0.85em;
            color: #999;
            display: flex;
            align-items: center;
        }

        .post-date i {
            margin-right: 5px;
        }

        /* Read More Button */
        .read-more {
            display: inline-block;
            padding: 10px 25px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-decoration: none;
            border-radius: 50px;
            font-weight: 500;
            align-self: flex-start;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            font-size: 0.9em;
        }

        .read-more:hover {
            transform: translateX(5px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }

        /* Meta Information */
        .meta-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 15px;
        }

        .tag {
            background: #f0f2f5;
            color: #666;
            padding: 5px 12px;
            border-radius: 50px;
            font-size: 0.8em;
            transition: all 0.3s ease;
        }

        .tag:hover {
            background: #667eea;
            color: white;
        }

        /* Loading Animation */
        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            body {
                padding: 20px 10px;
            }

            .blog-header h1 {
                font-size: 2em;
            }

            .blog-grid {
                grid-template-columns: 1fr;
            }

            .post-image {
                height: 180px;
            }
        }

        /* Empty State */
        .no-posts {
            text-align: center;
            padding: 60px 20px;
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            animation: fadeIn 1s ease;
        }

        .no-posts h2 {
            color: #333;
            margin-bottom: 15px;
        }

        .no-posts p {
            color: #666;
        }

        /* Featured Badge */
        .featured-badge {
            position: absolute;
            top: 15px;
            right: 15px;
            background: #ff6b6b;
            color: white;
            padding: 5px 15px;
            border-radius: 50px;
            font-size: 0.8em;
            font-weight: bold;
            box-shadow: 0 3px 10px rgba(255,107,107,0.3);
            z-index: 1;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="blog-header">
        <h1>📝 Our Blog</h1>
        <p>Stories, updates, and insights from our community</p>
    </div>

    <?php if($result->num_rows > 0): ?>
        <div class="blog-grid">
            <?php while($row = $result->fetch_assoc()): ?>
                <div class="blog-post">
                    <div class="post-image">
                        <?php if($row['image']): ?>
                            <img src="<?php echo htmlspecialchars($row['image']); ?>" alt="<?php echo htmlspecialchars($row['title']); ?>">
                        <?php else: ?>
                            <div class="no-image">
                                📷 No Image
                            </div>
                        <?php endif; ?>
                        
                        <?php if(isset($row['featured']) && $row['featured']): ?>
                            <span class="featured-badge">Featured</span>
                        <?php endif; ?>
                    </div>
                    
                    <div class="post-content">
                        <h2><?php echo htmlspecialchars($row['title']); ?></h2>
                        
                        <p><?php echo nl2br(htmlspecialchars(substr($row['content'], 0, 150))); ?>...</p>
                        
                        <div class="author-info">
                            <div class="author-avatar">
                                <?php echo strtoupper(substr($row['author'], 0, 1)); ?>
                            </div>
                            <div class="author-details">
                                <div class="author-name"><?php echo htmlspecialchars($row['author']); ?></div>
                                <div class="post-date">
                                    <span>📅</span>
                                    <?php echo date('F j, Y', strtotime($row['created_at'])); ?>
                                </div>
                            </div>
                        </div>

                        <?php if(isset($row['tags']) && $row['tags']): ?>
                            <div class="meta-tags">
                                <?php 
                                $tags = explode(',', $row['tags']);
                                foreach($tags as $tag): 
                                ?>
                                    <span class="tag">#<?php echo trim(htmlspecialchars($tag)); ?></span>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>

                        <button class="read-more" onclick="alert('Full post coming soon!')">Read More →</button>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <div class="no-posts">
            <h2>📭 No Blog Posts Yet</h2>
            <p>Check back soon for updates and stories from our community!</p>
        </div>
    <?php endif; ?>

</div>

</body>
</html>