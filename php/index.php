<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Student Registration System</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: "Poppins", sans-serif;
      background: linear-gradient(135deg, #007acc, #00c6ff);
      color: white;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      align-items: center;
    }

    header {
      width: 100%;
      padding: 20px;
      background: rgba(0, 0, 0, 0.2);
      text-align: center;
      font-size: 1.8rem;
      letter-spacing: 1px;
      font-weight: bold;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
 header h3 {
      display: flex;
      align-items: center;
      gap: 10px;
    }
    header h3 i {
      color: #ffdd57;
    }
    nav a {
      color: #fff;
      text-decoration: none;
      margin: 0 15px;
      font-weight: bold;
      transition: 0.3s;
      font-size: 19px;
    }

    nav a:hover {
      color: #ffdd57;
    }

    nav button {
      background: #ffdd57;
      color: #333;
      border: none;
      padding: 8px 15px;
      border-radius: 5px;
      cursor: pointer;
      font-weight: bold;
      transition: 0.3s;
    }

    nav button:hover {
      background: #ffc107;
    }

    section {
      flex: 1;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      text-align: center;
      padding: 40px;
    }

    h1 {
      font-size: 2.5rem;
      opacity: 0;
      transform: translateY(-20px);
      animation: fadeInUp 2s ease forwards;
    }

    p {
      margin-top: 15px;
      font-size: 1.1rem;
      opacity: 0;
      transform: translateY(20px);
      animation: fadeInUp 2s ease forwards;
      animation-delay: 0.5s;
    }

    button {
      margin-top: 25px;
      background: #ffdd57;
      color: #333;
      border: none;
      padding: 12px 25px;
      border-radius: 5px;
      font-size: 1rem;
      cursor: pointer;
      font-weight: bold;
      transition: 0.3s;
      opacity: 0;
      transform: translateY(20px);
      animation: fadeInUp 2s ease forwards;
      animation-delay: 1s;
    }

    button:hover {
      background: #ffc107;
      transform: scale(1.05);
    }

    @keyframes fadeInUp {
      0% {
        opacity: 0;
        transform: translateY(30px);
      }

      100% {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .service-cards {
      display: flex;
      justify-content: center;
      gap: 20px;
      margin-top: 20px;
    }

    .card {
      background: white;
      color: #007acc;
      padding: 30px;
      border-radius: 8px;
      width: 400px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .service h4 {
      font-size: 24px;
      margin-bottom: 10px;
    }

    .service p {
      font-size: 16px;
      color: #1324a3ff;
    }

    footer {
      width: 100%;
      background: #1d3c7eff;
      color: #ccc;
      padding: 30px 20px;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .footer-container {
      display: flex;
      justify-content: space-between;
      width: 100%;
      max-width: 1200px;
    }

    footer p {
      color: #ccc;
      font-size: 14px;
      margin-top: 10px;
    }

    footer a {
      color: #ffdd57;
      text-decoration: none;
    }

    @media (max-width: 768px) {
      .footer-container {
        flex-direction: column;
        text-align: center;
      }
    }

    #customAlert {
      display: none;
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.5);
      justify-content: center;
      align-items: center;
      z-index: 1000;
      animation: slideIn 0.5s ease forwards;
    }

    .alert-content {
      background: white;
      color: #333;
      padding: 25px 30px;
      border-radius: 12px;
      text-align: center;
      width: 350px;
      box-shadow: 0 5px 20px rgba(0, 0, 0, 0.3);
      transform: translateY(-100px);
    }

    .alert-content p {
      margin-bottom: 20px;
      font-size: 16px;
    }

    .alert-content button {
      background: #007acc;
      color: #fff;
      border: none;
      padding: 8px 15px;
      margin: 0 5px;
      border-radius: 6px;
      cursor: pointer;
      font-weight: bold;
      transition: 0.3s;
    }

    .alert-content button:hover {
      background: #005fa3;
    }

    @keyframes slideIn {
      0% {
        opacity: 0;
      }

      100% {
        opacity: 1;
      }
    }
  </style>
</head>

<body>

  <header>
    <h3><i class="fa-solid fa-graduation-cap"></i> Student Registration System</h3>
    <nav>
      <a href="index.php">Home</a>
      <a href="students.php">View Students</a>
      <button onclick="showAlert()">Register student</button>
    </nav>
  </header>

  <section>
    <h1 id="welcomeText"></h1>
    <p>Your simple and powerful web system to manage student data easily and efficiently.</p>
    <button onclick="location.href='register.php'">Get Started</button>
  </section>

  <section>
    <div class="service">
      <h4>Our Services</h4>
      <p>We provide student registration and record management.</p>
      <div class="service-cards">
        <div class="card">
          <h5>Register Students</h5>
          <p>Add new students easily.</p>
        </div>
        <div class="card">
          <h5>View Records</h5>
          <p>Check student details quickly.</p>
        </div>
      </div>
    </div>
  </section>


  <div id="customAlert">
    <div class="alert-content">
      <p>To register a student, you must be logged in first!</p>
      <button onclick="window.location.href='login.php'">Login</button>
      <button onclick="closeAlert()">Cancel</button>
    </div>
  </div>

  <footer>
    <div class="footer-container">
      <div class="footer-left">
        <h3>GRJ <span>DEVELOPER</span></h3>
        <p>Building smart systems for students and schools.</p>
      </div>
      <div class="footer-center">
        <h4>Quick Links</h4>
        <ul>
          <li><a href="index.html">Home</a></li>
          <li><a href="register.html">Register</a></li>
          <li><a href="view.php">View Students</a></li>
        </ul>
      </div>
      <div class="footer-right">
        <h4>Contact</h4>
        <p>Email: <a href="mailto:geovanipacis@gmail.com">geovanipacis@gmail.com</a></p>
        <p>© <?= date("Y") ?> GRJ DEVELOPER | All Rights Reserved</p>
      </div>
    </div>
  </footer>

  <script>
    const text = "Welcome to Student Registration System";
    const element = document.getElementById("welcomeText");
    let index = 0;

    function typeText() {
      if (index < text.length) {
        element.textContent += text.charAt(index);
        index++;
        setTimeout(typeText, 60);
      }
    }
    setTimeout(typeText, 500);


    function showAlert() {
      document.getElementById('customAlert').style.display = 'flex';
    }

    function closeAlert() {
      document.getElementById('customAlert').style.display = 'none';
    }
  </script>

</body>

</html>