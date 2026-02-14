<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Promoting Unity | NGO for Community Development</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Reset and Base Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary-color: #2ecc71;
            --primary-dark: #27ae60;
            --secondary-color: #3498db;
            --accent-color: #e74c3c;
            --dark-color: #2c3e50;
            --light-color: #ecf0f1;
            --text-color: #34495e;
            --white: #ffffff;
            --shadow: 0 5px 15px rgba(0,0,0,0.1);
            --transition: all 0.3s ease;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: var(--text-color);
            line-height: 1.6;
            overflow-x: hidden;
        }

        .container {
            width: 90%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        /* Header Styles */
        header {
            background: var(--white);
            box-shadow: var(--shadow);
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
            animation: slideDown 0.5s ease;
        }

        .header-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 0;
        }

        .logo h1 {
            font-size: 1.8rem;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-weight: 700;
        }

        .nav-links {
            display: flex;
            list-style: none;
            gap: 30px;
        }

        .nav-links a {
            text-decoration: none;
            color: var(--dark-color);
            font-weight: 500;
            transition: var(--transition);
            position: relative;
            padding-bottom: 5px;
        }

        .nav-links a:hover {
            color: var(--primary-color);
        }

        .nav-links a::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0;
            height: 2px;
            background: var(--primary-color);
            transition: var(--transition);
        }

        .nav-links a:hover::after {
            width: 100%;
        }

        .call {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: var(--white);
            padding: 10px 25px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            transition: var(--transition);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .call:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(46, 204, 113, 0.3);
        }

        /* Hero Section */
        .hero {
            height: 100vh;
            background: linear-gradient(135deg, rgba(0,0,0,0.7), rgba(0,0,0,0.5)), url('https://images.unsplash.com/photo-1488521787991-5c359e3e0c4f?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            color: var(--white);
            position: relative;
            margin-top: 70px;
        }

        .hero-content {
            animation: fadeInUp 1s ease;
            padding: 0 20px;
        }

        .hero-content h2 {
            font-size: 4rem;
            margin-bottom: 20px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }

        .hero-content p {
            font-size: 1.3rem;
            margin-bottom: 30px;
            opacity: 0.9;
        }

        .btn-primary {
            display: inline-block;
            padding: 15px 40px;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: var(--white);
            text-decoration: none;
            border-radius: 50px;
            font-weight: 600;
            font-size: 1.1rem;
            transition: var(--transition);
            border: none;
            cursor: pointer;
            box-shadow: 0 10px 20px rgba(46, 204, 113, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 30px rgba(46, 204, 113, 0.4);
        }

        /* About Section */
        .about {
            padding: 100px 0;
            background: var(--white);
        }

        .about h2 {
            text-align: center;
            font-size: 2.8rem;
            color: var(--dark-color);
            margin-bottom: 30px;
            position: relative;
            padding-bottom: 15px;
        }

        .about h2::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 4px;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border-radius: 2px;
        }

        .about p {
            text-align: center;
            font-size: 1.2rem;
            max-width: 800px;
            margin: 0 auto;
            color: var(--text-color);
            line-height: 1.8;
        }

        /* Services Section */
        .projects {
            padding: 100px 0;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        }

        .projects h2 {
            text-align: center;
            font-size: 2.8rem;
            color: var(--dark-color);
            margin-bottom: 50px;
            position: relative;
            padding-bottom: 15px;
        }

        .projects h2::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 4px;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border-radius: 2px;
        }

        .project-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
            margin-top: 50px;
        }

        .card {
            background: var(--white);
            border-radius: 20px;
            padding: 40px 30px;
            box-shadow: var(--shadow);
            transition: var(--transition);
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        }

        .card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
        }

        .card h3 {
            font-size: 1.5rem;
            color: var(--dark-color);
            margin-bottom: 20px;
        }

        .card p {
            color: var(--text-color);
            margin-bottom: 25px;
            line-height: 1.8;
        }

        .card .link {
            display: inline-block;
            padding: 10px 25px;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: var(--white);
            text-decoration: none;
            border-radius: 50px;
            font-weight: 500;
            transition: var(--transition);
        }

        .card .link:hover {
            transform: translateX(5px);
            box-shadow: 0 5px 15px rgba(46, 204, 113, 0.3);
        }

        /* Donate Section */
        .donate {
            padding: 100px 0;
            background: linear-gradient(135deg, rgba(46, 204, 113, 0.9), rgba(52, 152, 219, 0.9)), url('https://images.unsplash.com/photo-1532629345422-7515f3d16bb6?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            color: var(--white);
            text-align: center;
        }

        .donate h2 {
            font-size: 2.8rem;
            margin-bottom: 20px;
        }

        .donate p {
            font-size: 1.2rem;
            margin-bottom: 40px;
            opacity: 0.95;
        }

        .donate .btn-primary {
            background: var(--white);
            color: var(--primary-color);
            box-shadow: 0 10px 20px rgba(0,0,0,0.2);
        }

        .donate .btn-primary:hover {
            background: var(--dark-color);
            color: var(--white);
        }

        /* Footer */
        .footer {
            background: var(--dark-color);
            color: var(--white);
            padding: 80px 0 20px;
        }

        .footer-container {
            width: 90%;
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 40px;
            margin-bottom: 60px;
        }

        .footer-box h3 {
            font-size: 1.3rem;
            margin-bottom: 25px;
            position: relative;
            padding-bottom: 10px;
        }

        .footer-box h3::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 50px;
            height: 2px;
            background: var(--primary-color);
        }

        .footer-box p {
            margin-bottom: 15px;
            color: #bdc3c7;
            line-height: 1.8;
        }

        .footer-box ul {
            list-style: none;
        }

        .footer-box ul li {
            margin-bottom: 10px;
        }

        .footer-box ul li a {
            color: #bdc3c7;
            text-decoration: none;
            transition: var(--transition);
        }

        .footer-box ul li a:hover {
            color: var(--primary-color);
            padding-left: 5px;
        }

        .footer-box a {
            color: #bdc3c7;
            text-decoration: none;
            transition: var(--transition);
        }

        .footer-box a:hover {
            color: var(--primary-color);
        }

        .footer-bottom {
            text-align: center;
            padding-top: 20px;
            border-top: 1px solid rgba(255,255,255,0.1);
        }

        .footer-bottom p {
            color: #bdc3c7;
            font-size: 0.9rem;
        }

        /* Animations */
        @keyframes slideDown {
            from {
                transform: translateY(-100%);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .header-container {
                flex-direction: column;
                gap: 15px;
            }

            .nav-links {
                flex-wrap: wrap;
                justify-content: center;
                gap: 15px;
            }

            .hero-content h2 {
                font-size: 2.5rem;
            }

            .hero-content p {
                font-size: 1.1rem;
            }

            .about h2,
            .projects h2,
            .donate h2 {
                font-size: 2.2rem;
            }

            .project-cards {
                grid-template-columns: 1fr;
            }

            .footer-container {
                grid-template-columns: 1fr;
                text-align: center;
            }

            .footer-box h3::after {
                left: 50%;
                transform: translateX(-50%);
            }
        }

        /* Floating Animation for Cards */
        @keyframes float {
            0% {
                transform: translateY(0px);
            }
            50% {
                transform: translateY(-10px);
            }
            100% {
                transform: translateY(0px);
            }
        }

        .card:nth-child(1) { animation: float 6s ease-in-out infinite; }
        .card:nth-child(2) { animation: float 6s ease-in-out infinite 0.2s; }
        .card:nth-child(3) { animation: float 6s ease-in-out infinite 0.4s; }

        /* Smooth Scroll */
        html {
            scroll-behavior: smooth;
        }

        /* Mobile Menu Button (Hidden by default) */
        .mobile-menu {
            display: none;
            font-size: 1.5rem;
            cursor: pointer;
            color: var(--dark-color);
        }

        @media (max-width: 768px) {
            .mobile-menu {
                display: block;
            }

            .nav-links {
                display: none;
                position: absolute;
                top: 100%;
                left: 0;
                width: 100%;
                background: var(--white);
                flex-direction: column;
                padding: 20px;
                box-shadow: 0 10px 20px rgba(0,0,0,0.1);
            }

            .nav-links.active {
                display: flex;
            }
        }
    </style>
</head>
<body>
    <!-- Header / Navigation -->
    <header>
        <div class="container header-container">
            <div class="logo">
                <h1>Promoting Unity</h1>
            </div>
            <div class="mobile-menu" onclick="toggleMenu()">
                <i class="fas fa-bars"></i>
            </div>
            <nav>
                <ul class="nav-links" id="navLinks">
                    <li><a href="#home">Home</a></li>
                    <li><a href="#about">About</a></li>
                    <li><a href="#projects">Services</a></li>
                    <li><a href="#donate">Donate</a></li>
                    <li><a href="#contact">Contact</a></li>
                    <li><a href="../Support-hono/admin/adminlogin.php">Admin Login</a></li>
                </ul>
            </nav>
            <a href="tel:+250735287464" class="call">
                <i class="fas fa-phone-alt"></i> Call Us
            </a>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero" id="home">
        <div class="hero-content">
            <h2>Making the World a Better Place</h2>
            <p>Join us in creating positive change for communities in need.</p>
            <a href="#donate" class="btn-primary">Donate Now</a>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="about">
        <div class="container">
            <h2>About Us</h2>
            <p>Helping Hands improves lives through education, health, and community programs, creating lasting change. We believe in the power of unity and compassion to transform communities and build a better future for all.</p>
        </div>
    </section>

    <!-- Projects/Services Section -->
    <section id="projects" class="projects">
        <div class="container">
            <h2>Our Services</h2>
            <div class="project-cards">
                <div class="card">
                    <i class="fas fa-wheelchair" style="font-size: 3rem; color: var(--primary-color); margin-bottom: 20px;"></i>
                    <h3>Support People with Disabilities</h3>
                    <p>Empowering individuals with disabilities through education, care, and accessibility programs.</p>
                    <a href="./html/disa.html" class="link">Learn more</a>
                </div>

                <div class="card">
                    <i class="fas fa-child" style="font-size: 3rem; color: var(--primary-color); margin-bottom: 20px;"></i>
                    <h3>Orphan Children</h3>
                    <p>Providing care, education, and emotional support for orphaned children in need.</p>
                    <a href="#" class="link">Learn more</a>
                </div>

                <div class="card">
                    <i class="fas fa-home" style="font-size: 3rem; color: var(--primary-color); margin-bottom: 20px;"></i>
                    <h3>Support Low-Income Communities</h3>
                    <p>Bringing healthcare, education, and opportunities to underserved communities.</p>
                    <a href="#" class="link">Learn more</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Donate Section -->
    <section id="donate" class="donate">
        <div class="container">
            <h2>Support Our Cause</h2>
            <p>Your contribution helps us reach more people and make a bigger impact in communities that need it most.</p>
            <a href="./html/donar.html" class="btn-primary">Donate Now</a>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="about" style="background: var(--white);">
        <div class="container">
            <h2>Get In Touch</h2>
            <p style="margin-bottom: 30px;">Have questions? We'd love to hear from you. Reach out to us anytime.</p>
            <div style="display: flex; justify-content: center; gap: 30px; flex-wrap: wrap;">
                <div style="text-align: center;">
                    <i class="fas fa-map-marker-alt" style="font-size: 2rem; color: var(--primary-color); margin-bottom: 10px;"></i>
                    <p>Kigali, Rwanda</p>
                </div>
                <div style="text-align: center;">
                    <i class="fas fa-phone" style="font-size: 2rem; color: var(--primary-color); margin-bottom: 10px;"></i>
                    <p>+250 735 287 464</p>
                </div>
                <div style="text-align: center;">
                    <i class="fas fa-envelope" style="font-size: 2rem; color: var(--primary-color); margin-bottom: 10px;"></i>
                    <p>tumukundehonolyne@gmail.com</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-container">
            <!-- NGO Info -->
            <div class="footer-box">
                <h3>Promoting Unity</h3>
                <p>We are committed to improving lives through education, health, and community development programs.</p>
            </div>

            <!-- Quick Links -->
            <div class="footer-box">
                <h3>Quick Links</h3>
                <ul>
                    <li><a href="./index.php">Home</a></li>
                    <li><a href="#about">About Us</a></li>
                    <li><a href="#donate">Donate</a></li>
                    <li><a href="#contact">Contact</a></li>
                </ul>
            </div>

            <!-- Contact Info -->
            <div class="footer-box">
                <h3>Contact Us</h3>
                <p><i class="fas fa-map-marker-alt"></i> Kigali, Rwanda</p>
                <p><i class="fas fa-phone"></i> <a href="tel:+250735287464">+250 735 287 464</a></p>
                <p><i class="fas fa-envelope"></i> <a href="mailto:tumukundehonolyne@gmail.com">tumukundehonolyne@gmail.com</a></p>
            </div>
        </div>

        <!-- Bottom -->
        <div class="footer-bottom">
            <p>&copy; 2026 Promoting Unity NGO. All rights reserved.</p>
        </div>
    </footer>

    <script>
        // Mobile menu toggle
        function toggleMenu() {
            const navLinks = document.getElementById('navLinks');
            navLinks.classList.toggle('active');
        }

        // Close mobile menu when clicking a link
        document.querySelectorAll('.nav-links a').forEach(link => {
            link.addEventListener('click', () => {
                document.getElementById('navLinks').classList.remove('active');
            });
        });

        // Smooth scroll for navigation links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

      
        window.addEventListener('scroll', function() {
            const header = document.querySelector('header');
            if (window.scrollY > 100) {
                header.style.background = 'rgba(255, 255, 255, 0.95)';
                header.style.backdropFilter = 'blur(10px)';
            } else {
                header.style.background = 'var(--white)';
                header.style.backdropFilter = 'none';
            }
        });
    </script>
</body>
</html>