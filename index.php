<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Promoting Unity | NGO</title>
    <!-- Font Awesome 6 (free) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Leaflet for map -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Roboto, system-ui, sans-serif;
        }

        body {
            background-color: #f9fbfd;
            color: #1e2b3c;
            line-height: 1.5;
            transition: background-color 0.3s, color 0.3s;
        }

        /* DARK MODE STYLES */
        body.dark-mode {
            background-color: #121e26;
            color: #e2edf5;
        }
        body.dark-mode header,
        body.dark-mode .card,
        body.dark-mode .about,
        body.dark-mode .projects,
        body.dark-mode .donate,
        body.dark-mode .footer,
        body.dark-mode .testimonial-section,
        body.dark-mode .blog-section {
            background-color: #1c2a33;
            color: #d1e2ed;
        }
        body.dark-mode .hero-content {
            background: rgba(20, 40, 50, 0.8);
            border-color: #2f5568;
        }
        body.dark-mode .btn-primary {
            background-color: #24799e;
        }
        body.dark-mode .call {
            background: #1f4355;
            border-color: #3584a3;
            color: #d2ecff;
        }
        .dark-toggle {
            cursor: pointer;
            background: #eee;
            border-radius: 40px;
            padding: 0.4rem 1rem;
            font-weight: 600;
            margin-left: 1rem;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
        }

        /* HEADER */
        header {
            background-color: #ffffff;
            box-shadow: 0 6px 18px rgba(0, 40, 70, 0.06);
            position: sticky;
            top: 0;
            z-index: 100;
        }
        .header-container {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            padding: 0.8rem 2rem;
            max-width: 1400px;
            margin: 0 auto;
        }
        .logo h1 {
            font-size: 1.9rem;
            font-weight: 600;
            background: linear-gradient(145deg, #0b4e70, #1f7a8c);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .nav-links {
            display: flex;
            gap: 2.2rem;
            list-style: none;
        }
        .nav-links a {
            text-decoration: none;
            font-weight: 500;
            color: #1e3b4f;
            font-size: 1.1rem;
            transition: 0.2s;
            border-bottom: 2px solid transparent;
        }
        .nav-links a:hover {
            border-bottom-color: #1f7a8c;
            color: #0a4b64;
        }
        .call {
            background: #eef7fb;
            padding: 0.7rem 1.5rem;
            border-radius: 40px;
            text-decoration: none;
            color: #0f5e7a;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            border: 1px solid #b9d9e9;
        }
        .call:hover { background: #cde4f0; }

        /* HERO */
        .hero {
            background: linear-gradient(107deg, #d4e9f2 0%, #f0f9ff 100%);
            min-height: 420px;
            display: flex;
            align-items: center;
        }
        .hero-content {
            max-width: 700px;
            margin: 0 auto;
            text-align: center;
            padding: 4rem 2rem;
            border-radius: 40px;
            background: rgba(255, 255, 255, 0.75);
            backdrop-filter: blur(3px);
            box-shadow: 0 25px 35px -10px rgba(19, 73, 103, 0.2);
        }
        .hero-content h2 { font-size: 2.8rem; color: #09455c; }
        .hero-content p { font-size: 1.3rem; color: #1a4b61; margin-bottom: 2rem; }
        .btn-primary {
            background: #116b8f;
            color: white;
            font-size: 1.2rem;
            font-weight: 600;
            padding: 0.9rem 2.8rem;
            border-radius: 50px;
            text-decoration: none;
            display: inline-block;
            box-shadow: 0 8px 16px -5px #1f7a8c70;
            transition: 0.2s;
        }
        .btn-primary:hover { background: #0a4e6b; transform: translateY(-3px); }

        /* IMPACT COUNTER */
        .impact-stats {
            display: flex;
            justify-content: center;
            gap: 3rem;
            padding: 3rem 2rem;
            background: white;
            flex-wrap: wrap;
        }
        .stat-box { text-align: center; min-width: 150px; }
        .stat-box i { font-size: 3rem; color: #1f7a8c; margin-bottom: 0.5rem; }
        .stat-box h3 { font-size: 2.8rem; color: #09455c; }
        .stat-box p { font-size: 1.2rem; color: #266481; }

        /* ABOUT */
        .about { background: white; padding: 3rem 0; text-align: center; }
        .about h2 { font-size: 2.5rem; color: #103d52; }
        .about p { max-width: 750px; margin: 1.5rem auto; font-size: 1.3rem; background: #ecf7fd; padding: 2rem; border-radius: 60px; }

        /* PROJECTS */
        .projects { background: #f0f7fc; padding: 4rem 0; }
        .projects h2 { text-align: center; font-size: 2.5rem; color: #103d52; }
        .project-cards {
            display: flex; flex-wrap: wrap; gap: 2rem; justify-content: center; margin-top: 2rem;
        }
        .card {
            background: white; border-radius: 32px; padding: 2rem; flex: 1 1 260px; max-width: 320px;
            box-shadow: 0 15px 30px -12px rgba(24, 78, 109, 0.15);
            transition: 0.25s;
        }
        .card:hover { transform: translateY(-8px); }
        .card h3 { font-size: 1.7rem; color: #124f69; }
        .card p { margin: 1rem 0 1.5rem; }
        .card .link { text-decoration: none; font-weight: 600; color: #096b92; border-bottom: 2px solid #7fbad3; }

        /* DONATION BAR & SECTION */
        .donate {
            background: linear-gradient(130deg, #c9e3f0, #ffffff);
            padding: 3rem 0;
            text-align: center;
        }
        .fundraiser { max-width: 500px; margin: 2rem auto; background: white; padding: 2rem; border-radius: 40px; }
        .progress-bar { background: #ddd; height: 30px; border-radius: 30px; margin: 1rem 0; overflow: hidden; }
        .progress { background: #1f7a8c; height: 100%; width: 65%; color: white; line-height: 30px; }

        /* TESTIMONIAL CAROUSEL */
        .testimonial-section {
            background: white; padding: 3rem 2rem; text-align: center;
        }
        .testimonial-slider {
            max-width: 700px; margin: 2rem auto; position: relative; min-height: 150px;
        }
        .slide {
            display: none; font-size: 1.3rem; background: #eef7fb; padding: 2rem; border-radius: 40px;
        }
        .slide.active { display: block; }
        .slide h4 { margin-top: 1rem; color: #0b4e70; }
        .slider-btn {
            background: #1f7a8c; color: white; border: none; padding: 0.7rem 1.5rem; margin: 1rem;
            border-radius: 40px; cursor: pointer; font-size: 1.1rem;
        }

        /* MAP */
        #map { height: 300px; width: 100%; border-radius: 30px; margin: 2rem 0; }

        /* BLOG TEASER */
        .blog-section {
            background: #f5fafd; padding: 3rem 2rem; text-align: center;
        }
        .blog-card {
            background: white; border-radius: 30px; padding: 1.5rem; max-width: 300px; margin: 1rem auto;
        }

        /* VOLUNTEER FORM */
        .volunteer-section {
            background: #ffffff; padding: 3rem 2rem;
        }
        .vol-form {
            max-width: 600px; margin: 0 auto; background: #ecf7fd; padding: 2.5rem; border-radius: 50px;
        }
        .vol-form input, .vol-form select, .vol-form textarea {
            width: 100%; padding: 1rem; margin: 0.7rem 0; border-radius: 50px; border: 1px solid #b9d9e9;
        }
        .vol-form button { background: #116b8f; color: white; padding: 1rem 3rem; border: none; border-radius: 60px; font-size: 1.2rem; cursor: pointer; }

        /* WHATSAPP FLOAT */
        .whatsapp-float {
            position: fixed; bottom: 30px; right: 30px; background: #25D366; color: white; width: 60px; height: 60px;
            border-radius: 50%; text-align: center; font-size: 2.2rem; line-height: 60px; box-shadow: 0 8px 16px rgba(0,0,0,0.2);
            z-index: 999; transition: 0.2s;
        }
        .whatsapp-float:hover { transform: scale(1.1); background: #20b859; }

        /* SCROLL TOP */
        .scroll-top {
            position: fixed; bottom: 30px; left: 30px; background: #09455c; color: white; width: 50px; height: 50px;
            border-radius: 50%; text-align: center; font-size: 1.8rem; line-height: 50px; cursor: pointer; display: none; z-index: 999;
        }
        .scroll-top.show { display: block; }

        /* FOOTER */
        .footer { background: #142b38; color: #d9e9f2; padding-top: 3rem; }
        .footer-container {
            max-width: 1300px; margin: 0 auto; padding: 0 2rem 2rem; display: flex; flex-wrap: wrap; gap: 2.5rem;
        }
        .footer-box { flex: 1 1 240px; }
        .footer-box h3 { color: white; border-left: 5px solid #4797b9; padding-left: 1rem; }
        .footer-box a { color: #c3dae7; text-decoration: none; }
        .footer-bottom { background: #0c1f29; text-align: center; padding: 1.5rem; }

        /* DARK MODE TOGGLE */
        .dark-mode-toggle {
            background: #eef7fb; border: none; padding: 0.5rem 1.5rem; border-radius: 40px; font-weight: 600; cursor: pointer;
        }

        @media (max-width: 800px) {
            .header-container { flex-direction: column; }
            .impact-stats { flex-direction: column; gap: 1.5rem; }
        }
    </style>
</head>
<body>
    <header>
        <div class="header-container">
            <div class="logo"><h1>Promoting Unity</h1></div>
            <nav>
                <ul class="nav-links">
                    <li><a href="#">Home</a></li>
                    <li><a href="#donate">Donate</a></li>
                    <li><a href="./html/contact.html">Contact</a></li>
                    <li><a href="./admin/adminlogin.php">Admin Login</a></li>
                </ul>
            </nav>
            <div style="display: flex; gap: 1rem; align-items: center;">
                <a href="tel:+250735287464" class="call"><i class="fa-solid fa-phone"></i> Call Us</a>
                <button class="dark-mode-toggle" id="darkModeToggle"><i class="fa-solid fa-moon"></i> Dark</button>
            </div>
        </div>
    </header>

    
    <section class="hero">
        <div class="hero-content">
            <h2>Making the World a Better Place</h2>
            <p>Join us in creating positive change for communities in need.</p>
            <a href="#donate" class="btn-primary">Donate Now</a>
        </div>
    </section>
    <div class="impact-stats">
        <div class="stat-box"><i class="fa-solid fa-heart"></i><h3 class="counter" data-target="1500">0</h3><p>Lives Changed</p></div>
        <div class="stat-box"><i class="fa-solid fa-child"></i><h3 class="counter" data-target="450">0</h3><p>Orphans Supported</p></div>
        <div class="stat-box"><i class="fa-solid fa-hand-holding-heart"></i><h3 class="counter" data-target="75">0</h3><p>Active Projects</p></div>
    </div>

    <!-- About -->
    <section id="about" class="about"><div class="container"><h2>About Us</h2><p>Helping Hands improves lives through education, health, and community programs, creating lasting change.</p></div></section>

    <!-- Services -->
    <section id="projects" class="projects">
        <div class="container">
            <h2>Our Service</h2>
            <div class="project-cards">
                <div class="card"><h3>Support People with Disabilities</h3><p>Empowering individuals with disabilities.</p><a href="./html/disa.html" class="link">Learn more →</a></div>
                <div class="card"><h3>Orphan children</h3><p>Care, education, and support for orphans.</p><a href="./html/child.html" class="link">Learn more →</a></div>
                <div class="card"><h3>Low-Income Communities</h3><p>Healthcare and awareness.</p><a href="./html/lowincome.html" class="link">Learn more →</a></div>
            </div>
        </div>
    </section>

    <!-- Donate section with progress bar -->
    <section id="donate" class="donate">
        <div class="container">
            <h2>Support Our Cause</h2>
            <div class="fundraiser">
                <h3>Monthly Goal: $10,000</h3>
                <div class="progress-bar"><div class="progress" style="width:65%">$6,500</div></div>
                <p>65% of goal · 15 days left</p>
                <a href="./html/donar.html" class="btn-primary">Donate Now</a>
            </div>
        </div>
    </section>

    <!-- Testimonial Carousel -->
    <section class="testimonial-section">
        <h2>Voices of Hope</h2>
        <div class="testimonial-slider" id="testimonialSlider">
            <div class="slide active"><p>"This organization changed my life. I received education and hope."</p><h4>- Marie, Kigali</h4></div>
            <div class="slide"><p>"Thanks to the unity project, my family has a sustainable income."</p><h4>- Jean, Musanze</h4></div>
            <div class="slide"><p>"The health outreach saved my child from malaria. Forever grateful."</p><h4>- Chantal, Rubavu</h4></div>
        </div>
        <button class="slider-btn" id="prevSlide"><i class="fa-solid fa-arrow-left"></i> Prev</button>
        <button class="slider-btn" id="nextSlide">Next <i class="fa-solid fa-arrow-right"></i></button>
    </section>
    <section class="container" style="padding:2rem;">
        <h2 style="text-align:center;">Where we work</h2>
        <div id="map"></div>
    </section>
    <section class="blog-section">
        <h2>Latest Stories</h2>
        <div style="display:flex; gap:2rem; justify-content:center; flex-wrap:wrap;">
            <div class="blog-card"><h3>New school built in Kayonza</h3><p>Thanks to donors, 200 children have a classroom.</p><a href="#">Read more</a></div>
            <div class="blog-card"><h3>Medical campaign success</h3><p>Over 500 vaccinated in rural areas.</p><a href="#">Read more</a></div>
        </div>
    </section>
    <div id="emergencyBanner" style="background:#cc3300; color:white; text-align:center; padding:1rem; display:none;">
        ⚠️ URGENT: Flood relief appeal – your donation saves lives! <a href="#donate" style="color:yellow;">Donate now</a>
    </div>
    <button onclick="document.getElementById('emergencyBanner').style.display='block'" style="margin: 1rem auto; display:block;">🔔 Show emergency alert (demo)</button>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-container">
            <div class="footer-box"><h3>Promoting Unity</h3><p>Committed to improving lives through education, health, and community.</p></div>
            <div class="footer-box"><h3>Quick Links</h3><ul><li><a href="/index.php">Home</a></li><li><a href="/about.php">About</a></li><li><a href="/donate.php">Donate</a></li><li><a href="/contact.php">Contact</a></li></ul></div>
            <div class="footer-box"><h3>Contact</h3><p><i class="fa-solid fa-location-dot"></i> Kigali, Rwanda</p><p><i class="fa-solid fa-phone"></i> <a href="tel:+250735287464">+250 735 287 464</a></p><p><i class="fa-solid fa-envelope"></i> <a href="mailto:tumukundehonolyne@gmail.com">tumukundehonolyne@gmail.com</a></p></div>
        </div>
        <div class="footer-bottom"><p>&copy; 2026 Helping Hands NGO. All rights reserved.</p></div>
    </footer>

    <!-- WhatsApp Float -->
    <a href="https://wa.me/+250789984614" class="whatsapp-float" target="_blank"><i class="fa-brands fa-whatsapp"></i></a>
    <div class="scroll-top" id="scrollTop" onclick="window.scrollTo({top:0,behavior:'smooth'});"><i class="fa-solid fa-arrow-up"></i></div>

    <!-- Scripts -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        // Dark mode toggle
        const toggle = document.getElementById('darkModeToggle');
        toggle.addEventListener('click', ()=>{
            document.body.classList.toggle('dark-mode');
            toggle.innerHTML = document.body.classList.contains('dark-mode') ? '<i class="fa-solid fa-sun"></i> Light' : '<i class="fa-solid fa-moon"></i> Dark';
        });

        // Impact counter animation
        const counters = document.querySelectorAll('.counter');
        counters.forEach(counter => {
            const updateCount = () => {
                const target = +counter.getAttribute('data-target');
                const current = +counter.innerText;
                const increment = target / 100;
                if(current < target) {
                    counter.innerText = Math.ceil(current + increment);
                    setTimeout(updateCount, 20);
                } else {
                    counter.innerText = target;
                }
            };
            updateCount();
        });

        // Testimonial carousel
        const slides = document.querySelectorAll('.slide');
        let currentSlide = 0;
        document.getElementById('nextSlide').addEventListener('click', ()=>{
            slides[currentSlide].classList.remove('active');
            currentSlide = (currentSlide + 1) % slides.length;
            slides[currentSlide].classList.add('active');
        });
        document.getElementById('prevSlide').addEventListener('click', ()=>{
            slides[currentSlide].classList.remove('active');
            currentSlide = (currentSlide - 1 + slides.length) % slides.length;
            slides[currentSlide].classList.add('active');
        });

        
        const map = L.map('map').setView([-1.9441, 30.0619], 8);
        L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', { attribution: '&copy; OpenStreetMap' }).addTo(map);
        L.marker([-1.9441, 30.0619]).addTo(map).bindPopup('Head office Kigali').openPopup();
        L.marker([-1.5, 29.6333]).addTo(map).bindPopup('Musanze project');
        L.marker([-2.4833, 28.8833]).addTo(map).bindPopup('Rusizi health center');

        window.addEventListener('scroll', ()=>{
            const btn = document.getElementById('scrollTop');
            if(window.scrollY > 400) btn.classList.add('show'); else btn.classList.remove('show');
        });

      
        document.getElementById('volunteerForm').addEventListener('submit', (e)=>{ e.preventDefault(); alert('Thank you for volunteering! We will contact you.'); });
    </script>
    <style>
        img { transition: 0.2s; }
        img:hover { opacity: 0.8; }
    </style>
    
    <div style="text-align:center; padding:1rem;">
        <button onclick="alert('Share link copied!')"><i class="fa-brands fa-facebook"></i> Share</button>
        <button onclick="alert('Tweeted (demo)')"><i class="fa-brands fa-twitter"></i> Tweet</button>
    </div>

</body>
</html>