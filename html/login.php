<?php
session_start();

// DB connection
$conn = new mysqli("localhost", "root", "", "ngo_db");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$error = "";

if (isset($_POST['login'])) {

    $username = $_POST['username'];
    $password = $_POST['password'];

    // Use prepared statement
    $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows === 1) {

        $user = $result->fetch_assoc();

        // VERIFY hashed password
        if (password_verify($password, $user['password'])) {

            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];

            header("Location: dashboard.php");
            exit();

        } else {
            $error = "Wrong password!";
        }

    } else {
        $error = "User not found!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>Admin Login · Promoting Unity</title>
    <!-- Google Fonts + Font Awesome -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700;14..32,800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #EFF7F2 0%, #DFEAE6 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
            position: relative;
        }

        /* decorative background elements */
        body::before {
            content: "🤝";
            font-size: 280px;
            opacity: 0.04;
            position: absolute;
            bottom: -50px;
            left: -50px;
            pointer-events: none;
            transform: rotate(-10deg);
        }

        body::after {
            content: "❤️";
            font-size: 220px;
            opacity: 0.04;
            position: absolute;
            top: -40px;
            right: -30px;
            pointer-events: none;
        }

        .login-container {
            width: 100%;
            max-width: 500px;
            margin: 0 auto;
            animation: fadeSlideUp 0.5s ease-out;
        }

        @keyframes fadeSlideUp {
            from {
                opacity: 0;
                transform: translateY(25px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* main card */
        .login-card {
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(2px);
            border-radius: 2.5rem;
            box-shadow: 0 30px 50px -20px rgba(0, 40, 40, 0.35), 0 0 0 1px rgba(230, 126, 34, 0.15);
            overflow: hidden;
            transition: transform 0.2s ease;
        }

        .login-header {
            background: linear-gradient(115deg, #0A2E2E 0%, #1A5C5C 100%);
            padding: 2rem 2rem 1.8rem;
            text-align: center;
            color: white;
        }

        .logo-icon {
            background: #FFD966;
            width: 70px;
            height: 70px;
            border-radius: 60px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1rem;
            box-shadow: 0 10px 20px rgba(0,0,0,0.2);
        }

        .logo-icon i {
            font-size: 2.5rem;
            color: #0A2E2E;
        }

        .login-header h2 {
            font-size: 1.9rem;
            font-weight: 700;
            letter-spacing: -0.3px;
            margin-bottom: 0.3rem;
        }

        .login-header p {
            font-size: 0.9rem;
            opacity: 0.85;
        }

        .login-body {
            padding: 2.2rem 2rem 2.5rem;
        }

        /* error alert */
        .error-alert {
            background: #FEF2F0;
            border-left: 4px solid #E67E22;
            color: #A13E1A;
            padding: 0.9rem 1.2rem;
            border-radius: 1.2rem;
            margin-bottom: 1.8rem;
            display: flex;
            align-items: center;
            gap: 12px;
            font-weight: 500;
            font-size: 0.9rem;
            animation: shake 0.4s ease;
        }

        @keyframes shake {
            0%,100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }

        .input-group {
            margin-bottom: 1.6rem;
            position: relative;
        }

        .input-group label {
            display: block;
            font-weight: 600;
            font-size: 0.85rem;
            color: #2D5A5A;
            margin-bottom: 0.5rem;
            letter-spacing: -0.2px;
        }

        .input-icon {
            position: relative;
        }

        .input-icon i {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #A1C0B5;
            font-size: 1.1rem;
            transition: color 0.2s;
            pointer-events: none;
        }

        .input-icon input {
            width: 100%;
            padding: 0.9rem 1rem 0.9rem 2.8rem;
            border: 1.5px solid #E0EBE8;
            border-radius: 2rem;
            font-size: 1rem;
            font-family: 'Inter', sans-serif;
            background: #FEFEFC;
            transition: all 0.2s;
            outline: none;
        }

        .input-icon input:focus {
            border-color: #E67E22;
            box-shadow: 0 0 0 4px rgba(230, 126, 34, 0.15);
            background: white;
        }

        .input-icon input:focus + i {
            color: #E67E22;
        }

        /* checkbox row (optional extras) */
        .form-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 0.5rem 0 1.8rem;
            font-size: 0.85rem;
        }

        .checkbox {
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
        }

        .checkbox input {
            width: 18px;
            height: 18px;
            accent-color: #0A2E2E;
            cursor: pointer;
        }

        .forgot-link {
            color: #E67E22;
            text-decoration: none;
            font-weight: 500;
            transition: 0.2s;
        }

        .forgot-link:hover {
            text-decoration: underline;
            color: #CF711C;
        }

        /* login button */
        .login-btn {
            background: #0A2E2E;
            color: white;
            border: none;
            width: 100%;
            padding: 1rem;
            font-size: 1.1rem;
            font-weight: 700;
            border-radius: 3rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            cursor: pointer;
            transition: all 0.25s;
            font-family: 'Inter', sans-serif;
            box-shadow: 0 4px 8px rgba(0,0,0,0.05);
            margin-top: 0.5rem;
        }

        .login-btn i {
            font-size: 1.1rem;
            transition: transform 0.2s;
        }

        .login-btn:hover {
            background: #E67E22;
            transform: translateY(-2px);
            box-shadow: 0 12px 20px -8px rgba(230,126,34,0.4);
        }

        .login-btn:hover i {
            transform: translateX(3px);
        }

        /* footer extra */
        .login-footer {
            text-align: center;
            margin-top: 2rem;
            font-size: 0.8rem;
            color: #5F7E7A;
        }

        .login-footer a {
            color: #0A2E2E;
            text-decoration: none;
            font-weight: 600;
            border-bottom: 1px dashed #E67E22;
        }

        .login-footer a:hover {
            color: #E67E22;
        }

        /* demo hint (only for style showcase, can be removed) */
        .demo-hint {
            background: #FCF8E8;
            border-radius: 1rem;
            padding: 0.6rem 1rem;
            margin-top: 1.2rem;
            font-size: 0.75rem;
            text-align: center;
            color: #8B6F3C;
            border: 1px solid #FFE4B5;
        }

        @media (max-width: 500px) {
            .login-body {
                padding: 1.8rem;
            }
            .login-header h2 {
                font-size: 1.6rem;
            }
        }
        .register-link {
    display: inline-block;
    margin-top: 15px;
    padding: 10px 18px;
    background: #0A2E2E;        
    color: #FFD966;              
    font-weight: 600;
    font-size: 0.95rem;
    text-decoration: none;
    border-radius: 25px;         
    transition: all 0.3s ease;
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.register-link:hover {
    background: #E67E22;         
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 6px 12px rgba(0,0,0,0.15);
}
    </style>
</head>
<body>
<div class="login-container">
    <div class="login-card">
        <div class="login-header">
            <div class="logo-icon">
                <i class="fas fa-hand-holding-heart"></i>
            </div>
            <h2>Welcome back</h2>
            <p>Sign in to manage your dashboard</p>
        </div>

        <div class="login-body">
            <?php if(isset($error) && !empty($error)): ?>
                <div class="error-alert">
                    <i class="fas fa-exclamation-triangle" style="font-size: 1.2rem;"></i>
                    <span><?php echo htmlspecialchars($error); ?></span>
                </div>
            <?php endif; ?>

            <form method="POST" action="" id="loginForm">
                <div class="input-group">
                    <label><i class="fas fa-user" style="margin-right: 5px;"></i> Username</label>
                    <div class="input-icon">
                        <i class="fas fa-user-circle"></i>
                        <input type="text" name="username" placeholder="Enter your username" required autocomplete="username">
                    </div>
                </div>

                <div class="input-group">
                    <label><i class="fas fa-lock" style="margin-right: 5px;"></i> Password</label>
                    <div class="input-icon">
                        <i class="fas fa-key"></i>
                        <input type="password" name="password" placeholder="••••••••" required autocomplete="current-password">
                    </div>
                </div>

                <div class="form-options">
                    <label class="checkbox">
                        <input type="checkbox" name="remember" id="remember">
                        <span>Remember me</span>
                    </label>
                    <a href="#" class="forgot-link">Forgot password?</a>
                </div>

                <button type="submit" name="login" class="login-btn">
                    <span>Sign In</span>
                    <i class="fas fa-arrow-right"></i>
                </button>
                <!-- Optional demo info (only for visual) -->
                <div class="demo-hint">
                    <i class="fas fa-info-circle"></i> Demo credentials: admin / secret (or your db user)
                </div>
                 <a href="./register1.php" class="register-link">Don't have an Account? Register Here</a>
            </form>

            <div class="login-footer">
                <p><i class="fas fa-shield-alt"></i> Secure access · Promoting Unity NGO</p>
                <p><a href="/Support-hono/index.php"><i class="fas fa-home"></i> Back to homepage</a></p>
            </div>
        </div>
    </div>
</div>

<!-- simple client-side validation + micro-interaction -->
<script>
    (function() {
        const form = document.getElementById('loginForm');
        if(form) {
            form.addEventListener('submit', function(e) {
                const username = form.querySelector('input[name="username"]');
                const password = form.querySelector('input[name="password"]');
                let hasError = false;

                // clear previous inline errors (custom)
                const existingErrors = form.querySelectorAll('.inline-error');
                existingErrors.forEach(err => err.remove());

                if(!username.value.trim()) {
                    showInlineError(username, 'Username is required');
                    hasError = true;
                }
                if(!password.value.trim()) {
                    showInlineError(password, 'Password is required');
                    hasError = true;
                }

                if(hasError) {
                    e.preventDefault();
                }
            });

            function showInlineError(inputElement, message) {
                const errorSpan = document.createElement('div');
                errorSpan.className = 'inline-error';
                errorSpan.style.cssText = 'color:#E67E22; font-size:0.7rem; margin-top:5px; margin-left:12px; font-weight:500;';
                errorSpan.innerHTML = `<i class="fas fa-circle-exclamation"></i> ${message}`;
                inputElement.parentElement.appendChild(errorSpan);
                inputElement.style.borderColor = '#E67E22';
                inputElement.addEventListener('input', function() {
                    if(errorSpan) errorSpan.remove();
                    inputElement.style.borderColor = '#E0EBE8';
                }, { once: true });
            }
        }

        // remember me local storage simulation (optional)
        const rememberCheck = document.getElementById('remember');
        const storedUser = localStorage.getItem('rem_username');
        if(storedUser && document.querySelector('input[name="username"]')) {
            document.querySelector('input[name="username"]').value = storedUser;
            if(rememberCheck) rememberCheck.checked = true;
        }
        if(rememberCheck) {
            rememberCheck.addEventListener('change', function(e) {
                const usernameField = document.querySelector('input[name="username"]');
                if(e.target.checked && usernameField.value.trim()) {
                    localStorage.setItem('rem_username', usernameField.value.trim());
                } else if(!e.target.checked) {
                    localStorage.removeItem('rem_username');
                }
            });
        }
    })();
</script>
</body>
</html>

<?php

?>