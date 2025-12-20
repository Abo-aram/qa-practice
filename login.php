<?php
session_start();
if (isset($_SESSION['user_id'])) {
    echo "You are already logged in.";
    header("Location:homepage.php");
    exit();
}


?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>InstaFix - Login</title>
    <link rel="stylesheet" href="css/login.css">
</head>
<body>
    <div class="login-container">
        <div class="login-box">
            <h2>Login</h2>
            <p class="subtitle">Enter your credentials to access your account</p>
            
            <div id="errorBox" class="error-container hidden">
                <span id="errorText">Invalid email or password</span>
            </div>

            <form id="loginForm">
                <div class="input-group">
                    <label for="loginEmail">Email Address</label>
                    <input type="email" id="loginEmail" placeholder="admin@test.com" required>
                </div>

                <div class="input-group">
    <label for="loginPassword">Password</label>
    <div style="display: flex; gap: 10px">
        <input type="password" id="loginPassword" placeholder="••••••••" required>
        <button type="button" id="togglePass" style="width: 70px;">Show</button>
    </div>
</div>

                <button type="submit" id="loginBtn">Sign In</button>
            </form>
            
            <p class="footer-text">Don't have an account? <a href="index.php">Register here</a></p>
        </div>
    </div>

    <script src="js/login.js"></script>
</body>
</html>