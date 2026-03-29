
<?php
session_start();

   $servename= "localhost";
   $user = "root";
   $password = "";
   $db="ngo_db";

   $conn =new mysqli($servename,$user,$password,$db);

   if($conn -> connect_error){
    die("connection failed" . $conn->connect_error);
   }else{
   //  echo"well";
   }



$error = '';
$success = '';

if(isset($_POST['register'])) {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validate inputs
    if(empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        $error = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    } else {
        // Hash password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Check if username or email exists
        $stmt = $conn->prepare("SELECT id FROM users WHERE username=? OR email=?");
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        $stmt->store_result();

        if($stmt->num_rows > 0) {
            $error = "Username or email already exists.";
        } else {
            // Insert user
            $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $username, $email, $hashed_password);

            if($stmt->execute()) {
                $success = "Registration successful! <a href='login1.php'>Login here</a>";
            } else {
                $error = "Something went wrong. Try again.";
            }
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>Create Account · Promoting Unity</title>
    <!-- Google Fonts + Font Awesome 6 -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700;14..32,800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: linear-gradient(145deg, #EFF7F2 0%, #E2EBE7 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
            position: relative;
        }

        /* decorative organic shapes */
        body::before {
            content: "🌱";
            font-size: 280px;
            opacity: 0.04;
            position: absolute;
            bottom: -60px;
            left: -40px;
            pointer-events: none;
            transform: rotate(-8deg);
        }

        body::after {
            content: "🤝";
            font-size: 240px;
            opacity: 0.04;
            position: absolute;
            top: -30px;
            right: -30px;
            pointer-events: none;
        }

        .register-container {
            width: 100%;
            max-width: 580px;
            margin: 0 auto;
            animation: fadeSlideUp 0.5s cubic-bezier(0.2, 0.9, 0.4, 1.1);
        }

        @keyframes fadeSlideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* main card */
        .register-card {
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(2px);
            border-radius: 2.5rem;
            box-shadow: 0 30px 55px -20px rgba(0, 40, 40, 0.4), 0 0 0 1px rgba(230, 126, 34, 0.12);
            overflow: hidden;
            transition: transform 0.2s;
        }

        .register-header {
            background: linear-gradient(115deg, #0A2E2E 0%, #1A5C5C 100%);
            padding: 2rem 2rem 1.8rem;
            text-align: center;
            color: white;
        }

        .logo-icon {
            background: #FFD966;
            width: 72px;
            height: 72px;
            border-radius: 60px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.2rem;
            box-shadow: 0 12px 20px -8px rgba(0,0,0,0.25);
        }

        .logo-icon i {
            font-size: 2.5rem;
            color: #0A2E2E;
        }

        .register-header h2 {
            font-size: 2rem;
            font-weight: 700;
            letter-spacing: -0.3px;
            margin-bottom: 0.3rem;
        }

        .register-header p {
            font-size: 0.9rem;
            opacity: 0.85;
        }

        .register-body {
            padding: 2rem 2rem 2.2rem;
        }

        /* alert messages */
        .alert {
            padding: 1rem 1.2rem;
            border-radius: 1.2rem;
            margin-bottom: 1.8rem;
            display: flex;
            align-items: center;
            gap: 12px;
            font-weight: 500;
            font-size: 0.9rem;
            animation: slideAlert 0.3s ease;
        }

        @keyframes slideAlert {
            from { opacity: 0; transform: translateY(-8px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .alert-error {
            background: #FEF2F0;
            border-left: 4px solid #E67E22;
            color: #B64A1A;
        }

        .alert-success {
            background: #E6F9ED;
            border-left: 4px solid #2E7D5E;
            color: #1C5E48;
        }

        .alert a {
            color: #0A2E2E;
            font-weight: 700;
            text-decoration: underline;
        }

        .input-group {
            margin-bottom: 1.5rem;
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
            box-shadow: 0 0 0 4px rgba(230, 126, 34, 0.12);
            background: white;
        }

        .input-icon input:focus + i {
            color: #E67E22;
        }

        /* password strength indicator */
        .password-strength {
            margin-top: 0.5rem;
            font-size: 0.7rem;
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
        }

        .strength-bar {
            height: 4px;
            width: 60px;
            background: #E0EBE8;
            border-radius: 4px;
            overflow: hidden;
            display: inline-block;
        }

        .strength-fill {
            width: 0%;
            height: 100%;
            background: #E67E22;
            transition: width 0.2s;
        }

        /* terms checkbox */
        .terms-group {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            margin: 1rem 0 1.8rem;
            font-size: 0.8rem;
            color: #4F6E6A;
        }

        .terms-group input {
            width: 18px;
            height: 18px;
            margin-top: 2px;
            accent-color: #0A2E2E;
            cursor: pointer;
        }

        .terms-group a {
            color: #E67E22;
            text-decoration: none;
            font-weight: 600;
        }

        .register-btn {
            background: #0A2E2E;
            color: white;
            border: none;
            width: 100%;
            padding: 1rem;
            font-size: 1rem;
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
        }

        .register-btn i {
            font-size: 1rem;
            transition: transform 0.2s;
        }

        .register-btn:hover {
            background: #E67E22;
            transform: translateY(-2px);
            box-shadow: 0 12px 20px -8px rgba(230,126,34,0.4);
        }

        .register-btn:hover i {
            transform: translateX(3px);
        }

        .login-redirect {
            text-align: center;
            margin-top: 1.8rem;
            font-size: 0.85rem;
            color: #5F7E7A;
        }

        .login-redirect a {
            color: #0A2E2E;
            text-decoration: none;
            font-weight: 700;
            border-bottom: 1px dashed #E67E22;
        }

        .login-redirect a:hover {
            color: #E67E22;
        }

        .footer-note {
            text-align: center;
            margin-top: 1.2rem;
            font-size: 0.7rem;
            color: #8BA8A3;
        }

        @media (max-width: 550px) {
            .register-body {
                padding: 1.8rem;
            }
            .register-header h2 {
                font-size: 1.7rem;
            }
        }

        .inline-error {
            font-size: 0.7rem;
            color: #E67E22;
            margin-top: 5px;
            margin-left: 12px;
            font-weight: 500;
        }
    </style>
</head>
<body>
<div class="register-container">
    <div class="register-card">
        <div class="register-header">
            <div class="logo-icon">
                <i class="fas fa-hands-helping"></i>
            </div>
            <h2>Join the movement</h2>
            <p>Create an account to access volunteer tools</p>
        </div>

        <div class="register-body">
            <?php if(isset($error) && !empty($error)): ?>
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-circle"></i>
                    <span><?php echo htmlspecialchars($error); ?></span>
                </div>
            <?php endif; ?>

            <?php if(isset($success) && !empty($success)): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    <span><?php echo $success; ?></span>
                </div>
            <?php endif; ?>

            <form method="POST" action="" id="registerForm">
                <div class="input-group">
                    <label><i class="fas fa-user"></i> Username</label>
                    <div class="input-icon">
                        <i class="fas fa-user-circle"></i>
                        <input type="text" name="username" id="username" placeholder="e.g., unity_champion" required autocomplete="username" value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>">
                    </div>
                </div>

                <div class="input-group">
                    <label><i class="fas fa-envelope"></i> Email address</label>
                    <div class="input-icon">
                        <i class="fas fa-envelope"></i>
                        <input type="email" name="email" id="email" placeholder="hello@example.com" required autocomplete="email" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                    </div>
                </div>

                <div class="input-group">
                    <label><i class="fas fa-lock"></i> Password</label>
                    <div class="input-icon">
                        <i class="fas fa-key"></i>
                        <input type="password" name="password" id="password" placeholder="Create a strong password" required autocomplete="new-password">
                    </div>
                    <div class="password-strength" id="strengthWrapper">
                        <span>Password strength:</span>
                        <div class="strength-bar"><div class="strength-fill" id="strengthFill"></div></div>
                        <span id="strengthText" style="font-weight:500;">—</span>
                    </div>
                </div>

                <div class="input-group">
                    <label><i class="fas fa-check-circle"></i> Confirm password</label>
                    <div class="input-icon">
                        <i class="fas fa-shield-alt"></i>
                        <input type="password" name="confirm_password" id="confirm_password" placeholder="Confirm your password" required autocomplete="off">
                    </div>
                </div>

                <div class="terms-group">
                    <input type="checkbox" name="terms" id="terms" required>
                    <label for="terms">I agree to the <a href="#">Terms of Service</a> and <a href="#">Privacy Policy</a>.</label>
                </div>

                <button type="submit" name="register" class="register-btn">
                    <span>Create account</span>
                    <i class="fas fa-arrow-right"></i>
                </button>

                <div class="login-redirect">
                    <i class="fas fa-sign-in-alt"></i> Already have an account? <a href="login1.php">Sign in here</a>
                </div>
                <div class="footer-note">
                    <i class="fas fa-shield-heart"></i> Your data is safe with Promoting Unity
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    (function() {
        // realtime password strength + match indicator
        const passwordInput = document.getElementById('password');
        const confirmInput = document.getElementById('confirm_password');
        const strengthFill = document.getElementById('strengthFill');
        const strengthText = document.getElementById('strengthText');

        function evaluateStrength(pw) {
            if (!pw) return { score: 0, label: 'Very weak' };
            let score = 0;
            if (pw.length >= 6) score++;
            if (pw.length >= 10) score++;
            if (/[A-Z]/.test(pw)) score++;
            if (/[0-9]/.test(pw)) score++;
            if (/[^A-Za-z0-9]/.test(pw)) score++;
            score = Math.min(score, 4);
            const labels = ['Very weak', 'Weak', 'Fair', 'Good', 'Strong'];
            const colors = ['#E67E22', '#E6A157', '#F4B942', '#6DA55A', '#2E7D5E'];
            return { score: score, label: labels[score], color: colors[score] };
        }

        function updateStrength() {
            const val = passwordInput.value;
            const { score, label, color } = evaluateStrength(val);
            const percent = (score / 4) * 100;
            strengthFill.style.width = percent + '%';
            strengthFill.style.backgroundColor = color;
            strengthText.textContent = label;
            strengthText.style.color = color;
        }

        passwordInput.addEventListener('input', updateStrength);
        updateStrength();

        // confirm password realtime check
        function validateMatch() {
            const pass = passwordInput.value;
            const confirm = confirmInput.value;
            const errorDiv = document.getElementById('confirmError');
            if (confirm !== '' && pass !== confirm) {
                if (!errorDiv) {
                    const err = document.createElement('div');
                    err.id = 'confirmError';
                    err.className = 'inline-error';
                    err.innerHTML = '<i class="fas fa-exclamation-triangle"></i> Passwords do not match';
                    confirmInput.parentElement.appendChild(err);
                    confirmInput.style.borderColor = '#E67E22';
                }
            } else {
                if (errorDiv) errorDiv.remove();
                confirmInput.style.borderColor = '#E0EBE8';
            }
        }

        confirmInput.addEventListener('input', validateMatch);
        passwordInput.addEventListener('input', validateMatch);

        // final form validation before submit
        const form = document.getElementById('registerForm');
        form.addEventListener('submit', function(e) {
            let hasError = false;
            // remove existing inline errors
            document.querySelectorAll('.inline-error').forEach(el => el.remove());

            const username = document.getElementById('username');
            const email = document.getElementById('email');
            const password = passwordInput;
            const confirm = confirmInput;
            const terms = document.getElementById('terms');

            if (!username.value.trim()) {
                showInlineError(username, 'Username is required');
                hasError = true;
            }
            const emailVal = email.value.trim();
            if (!emailVal) {
                showInlineError(email, 'Email address is required');
                hasError = true;
            } else if (!/^\S+@\S+\.\S+$/.test(emailVal)) {
                showInlineError(email, 'Enter a valid email (e.g., name@domain.com)');
                hasError = true;
            }
            if (!password.value) {
                showInlineError(password, 'Password cannot be empty');
                hasError = true;
            } else if (password.value.length < 6) {
                showInlineError(password, 'Password must be at least 6 characters');
                hasError = true;
            }
            if (!confirm.value) {
                showInlineError(confirm, 'Please confirm your password');
                hasError = true;
            } else if (password.value !== confirm.value) {
                showInlineError(confirm, 'Passwords do not match');
                hasError = true;
            }
            if (!terms.checked) {
                const termsGroup = document.querySelector('.terms-group');
                const errSpan = document.createElement('div');
                errSpan.className = 'inline-error';
                errSpan.style.marginTop = '-8px';
                errSpan.style.marginBottom = '8px';
                errSpan.innerHTML = '<i class="fas fa-check-circle"></i> You must agree to the terms';
                termsGroup.parentNode.insertBefore(errSpan, termsGroup.nextSibling);
                hasError = true;
            }

            if (hasError) {
                e.preventDefault();
            }
        });

        function showInlineError(inputElement, message) {
            const errorSpan = document.createElement('div');
            errorSpan.className = 'inline-error';
            errorSpan.innerHTML = `<i class="fas fa-circle-exclamation"></i> ${message}`;
            inputElement.parentElement.appendChild(errorSpan);
            inputElement.style.borderColor = '#E67E22';
            inputElement.addEventListener('input', function() {
                if (errorSpan) errorSpan.remove();
                inputElement.style.borderColor = '#E0EBE8';
            }, { once: true });
        }

        // if success message contains link, make it clickable
        const successMsg = document.querySelector('.alert-success');
        if(successMsg && successMsg.innerHTML.includes('Login here')) {
            // already handled by php echo link
        }
    })();
</script>
</body>
</html>

<?php

?> 