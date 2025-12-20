<?php
session_start();

// Security Check: If no user is logged in, kick them back to login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Get the user's name from the session (set during login_api.php)
$userName = $_SESSION['full_name'] ?? "User";
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>InstaFix - Home</title>
    <link rel="stylesheet" href="css/homepage.css">
</head>
<body>

    <nav class="navbar">
        <div class="nav-container">
            <div class="logo">InstaFix</div>
            <ul class="nav-links">
                <li><a href="#" class="active">Home</a></li>
                <li><a href="#">Requests</a></li>
                <li class="user-menu">
                    <a href="profile.php" id="profileLink">
                        <span class="user-icon">👤</span> 
                        <span id="navUserName"><?php echo htmlspecialchars($userName); ?></span>
                    </a>
                </li>
                <li><a href="logout.php" class="logout-btn">Logout</a></li>
            </ul>
        </div>
    </nav>

    <main class="content">
        <header class="welcome-section">
            <h1>Welcome back, <?php echo htmlspecialchars($userName); ?>!</h1>
            <p>Your repair hero dashboard is ready. What do you need help with today?</p>
        </header>

        <section class="quick-actions">
            <div class="card">
                <h3>New Repair Request</h3>
                <p>Broken screen? Leaky pipe? Submit a new request here.</p>
                <button class="action-btn">Start Request</button>
            </div>
            <div class="card">
                <h3>Active Tasks</h3>
                <p>You have 0 active repairs at the moment.</p>
                <button class="action-btn secondary">View History</button>
            </div>
        </section>
    </main>

</body>
</html>