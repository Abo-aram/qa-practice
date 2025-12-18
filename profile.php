<?php
session_start();
require_once 'db_config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

// Fetch current user data to pre-fill the form
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>InstaFix - Edit Profile</title>
    <link rel="stylesheet" href="css/profile.css" />
</head>
<body>
    <div class="profile-container">
        <h2>User Settings</h2>
        <p id="statusMsg" class="hidden"></p>

        <form id="profileForm">
            <div class="input-group">
                <label for="bio">Short Bio</label>
                <textarea id="bio" rows="4"><?php echo htmlspecialchars($user['bio'] ?? ''); ?></textarea>
            </div>

            <div class="input-group">
                <label for="country">Country</label>
                <select id="country">
                    <option value="iq" <?php if($user['country'] == 'iq') echo 'selected'; ?>>Iraq</option>
                    <option value="us" <?php if($user['country'] == 'us') echo 'selected'; ?>>USA</option>
                    <option value="uk" <?php if($user['country'] == 'uk') echo 'selected'; ?>>UK</option>
                </select>
            </div>

            <div class="input-group">
                <label>Gender</label>
                <div class="radio-group">
                    <input type="radio" name="gender" value="male" id="male" <?php if($user['gender'] == 'male') echo 'checked'; ?>> <label for="male">Male</label>
                    <input type="radio" name="gender" value="female" id="female" <?php if($user['gender'] == 'female') echo 'checked'; ?>> <label for="female">Female</label>
                </div>
            </div>

            <div class="input-group checkbox-group">
                <input type="checkbox" id="newsletter" <?php if($user['newsletter']) echo 'checked'; ?>>
                <label for="newsletter">Subscribe to weekly newsletter</label>
            </div>

            <button type="submit" id="saveBtn">Save Profile</button>
            <a href="homepage.php" class="back-link">Back to Home</a>
        </form>
    </div>
    <script src="js/profile.js"></script>
</body>
</html>