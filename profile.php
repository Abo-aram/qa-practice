<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>InstaFix | Account Settings</title>
    <link rel="stylesheet" href="css/profile1.css">
</head>
<body>

<div class="app-container">
    <div class="profile-card">
        <aside class="sidebar">
            <div class="avatar-wrapper">
                <img src="uploads/default_avatar.png" id="avatar-preview" alt="Avatar">
                <div class="upload-overlay" onclick="document.getElementById('avatar-input').click()">
                    <span>Change Photo</span>
                </div>
                <input type="file" id="avatar-input" hidden accept="image/*">
            </div>

            <div class="sidebar-info">
                <h3 id="side-name">Loading...</h3>
                <p id="side-email">user@example.com</p>
            </div>

            <nav class="sidebar-nav">
                <a href="homepage.php">Dashboard</a>
                <a href="logout.php" class="logout-link">Logout</a>
            </nav>
        </aside>

        <main class="content-area">
            <form id="profile-form">

                <section class="form-section">
                    <div class="section-header">
                        <h2>Personal Details</h2>
                        <p>Update your public information.</p>
                    </div>

                    <div class="input-grid">
                        <div class="field">
                            <label>Full Name</label>
                            <input type="text" id="full_name" name="full_name" required>
                        </div>
                        <div class="field">
                            <label>Email (Locked)</label>
                            <input type="email" id="email" disabled>
                        </div>
                    </div>

                    <div class="field">
                        <label>Bio</label>
                        <textarea id="bio" name="bio"></textarea>
                    </div>

                    <div class="input-grid">
                        <div class="field">
                            <label>Country</label>
                            <select id="country" name="country">
                                <option value="Iraq">Iraq</option>
                                <option value="USA">USA</option>
                                <option value="UK">UK</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>

                        <div class="field">
                            <label>Gender</label>
                            <div class="pill-group">
                                <input type="radio" name="gender" value="Male" id="g-male" hidden>
                                <label for="g-male">Male</label>

                                <input type="radio" name="gender" value="Female" id="g-female" hidden>
                                <label for="g-female">Female</label>

                                <input type="radio" name="gender" value="Prefer not to say" id="g-none" hidden>
                                <label for="g-none">N/A</label>
                            </div>
                        </div>
                    </div>

                    <div class="field checkbox-field">
                        <label class="switch">
                            <input type="checkbox" id="newsletter" name="newsletter">
                            <span class="slider"></span>
                        </label>
                        <span>Subscribe to product newsletter</span>
                    </div>
                </section>

                <hr class="divider">

                <section class="form-section">
                    <div class="section-header">
                        <h2>Security</h2>
                        <p>Change your password.</p>
                    </div>

                    <div class="input-grid">
                        <div class="field">
                            <label>New Password</label>
                            <input type="password" id="new_password" name="new_password">
                        </div>
                        <div class="field">
                            <label>Confirm New Password</label>
                            <input type="password" id="confirm_password">
                        </div>
                    </div>
                </section>

                <div class="form-actions">
                    <button type="submit" class="save-btn" id="save-btn">Save Changes</button>
                </div>
            </form>
        </main>
    </div>
</div>

<!-- Toast Container -->
<div id="toast-container"></div>

<script src="js/profile1.js"></script>
</body>
</html>
