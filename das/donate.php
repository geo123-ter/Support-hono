<?php
session_start();
include 'db2.php';

$message = '';

if (isset($_POST['donate'])) {
    $name   = $_POST['name'];
    $email  = $_POST['email'];
    $amount = $_POST['amount'];
    $note   = $_POST['message'];

    if ($name && $email && $amount) {
        $stmt = $conn->prepare(
            "INSERT INTO donations (name, email, amount, message) VALUES (?, ?, ?, ?)"
        );
        $stmt->bind_param("ssds", $name, $email, $amount, $note);

        if ($stmt->execute()) {
            $message = "Thank you for your donation!";
        } else {
            $message = "Error saving donation.";
        }
        $stmt->close();
    } else {
        $message = "Please fill all required fields.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Donate | Helping Hands NGO</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        /* ===== RESET & BASE ===== */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: Arial, Helvetica, sans-serif;
}

body {
    background-color: #f4f9ff;
    color: #333;
    line-height: 1.6;
}

/* ===== CONTAINER ===== */
.container {
    width: 90%;
    max-width: 1100px;
    margin: auto;
}

/* ===== HEADER ===== */
header {
    background: #ffffff;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    position: sticky;
    top: 0;
    z-index: 1000;
}

.header-container {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem 0;
}

.logo h1 {
    color: #007BFF;
    font-size: 1.8rem;
}

.nav-links {
    list-style: none;
    display: flex;
    gap: 1.5rem;
}

.nav-links a {
    text-decoration: none;
    color: #333;
    font-weight: bold;
}

.nav-links a:hover,
.nav-links .active {
    color: #007BFF;
}

/* ===== DONATION SECTION ===== */
.donation-section {
    padding: 4rem 0;
}

.donation-container {
    background: #ffffff;
    max-width: 600px;
    margin: auto;
    padding: 2.5rem;
    border-radius: 10px;
    box-shadow: 0 8px 20px rgba(0,0,0,0.12);
    text-align: center;
}

.donation-container h2 {
    color: #007BFF;
    margin-bottom: 0.5rem;
}

.donation-container p {
    color: #555;
    margin-bottom: 2rem;
}

/* ===== FORM ===== */
.donation-form .form-group {
    text-align: left;
    margin-bottom: 1.3rem;
}

.donation-form label {
    display: block;
    font-weight: bold;
    margin-bottom: 0.4rem;
}

.donation-form input,
.donation-form textarea {
    width: 100%;
    padding: 0.75rem;
    border-radius: 5px;
    border: 1px solid #ccc;
    font-size: 1rem;
}

.donation-form input:focus,
.donation-form textarea:focus {
    border-color: #007BFF;
    outline: none;
}

/* ===== BUTTON ===== */
.btn-primary {
    background: #007BFF;
    color: #ffffff;
    border: none;
    padding: 0.8rem 2.2rem;
    border-radius: 5px;
    font-size: 1rem;
    font-weight: bold;
    cursor: pointer;
    transition: background 0.3s ease;
}

.btn-primary:hover {
    background: #0056b3;
}

/* ===== MESSAGE ===== */
.form-message {
    background: #e7f2ff;
    color: #0056b3;
    padding: 0.8rem;
    border-radius: 5px;
    margin-bottom: 1.2rem;
    font-weight: bold;
}

/* ===== FOOTER ===== */
footer {
    background: #007BFF;
    color: #ffffff;
    text-align: center;
    padding: 1.5rem 0;
    margin-top: 4rem;
}

/* ===== RESPONSIVE ===== */
@media (max-width: 600px) {
    .header-container {
        flex-direction: column;
        gap: 1rem;
    }

    .nav-links {
        flex-direction: column;
        gap: 0.8rem;
    }
}

    </style>
</head>
<body>

<!-- Header -->
<header>
    <div class="container header-container">
        <div class="logo">
            <h1>Helping Hands</h1>
        </div>
        <nav>
            <ul class="nav-links">
                <li><a href="../index.php">Home</a></li>
                <li><a href="donate.php" class="active">Donate</a></li>
            </ul>
        </nav>
    </div>
</header>

<!-- Donation Section -->
<section class="donation-section">
    <div class="container donation-container">
        <h2>Make a Donation</h2>
        <p>Your support helps us change lives and build stronger communities.</p>

        <?php if (!empty($message)) : ?>
            <div class="form-message">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="donation-form">
            <div class="form-group">
                <label>Full Name</label>
                <input type="text" name="name" required>
            </div>

            <div class="form-group">
                <label>Email Address</label>
                <input type="email" name="email" required>
            </div>

            <div class="form-group">
                <label>Donation Amount</label>
                <input type="number" name="amount" step="0.01" required>
            </div>

            <div class="form-group">
                <label>Message (optional)</label>
                <textarea name="message" rows="4"></textarea>
            </div>

            <button type="submit" name="donate" class="btn-primary">
                Donate Now
            </button>
        </form>
    </div>
</section>

<!-- Footer -->
<footer>
    <p>&copy; 2026 Helping Hands NGO. All rights reserved.</p>
</footer>

</body>
</html>
