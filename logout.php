<?php
// 1. Initialize the session to access it
session_start();

// 2. Unset all session variables (clear the $_SESSION array)
$_SESSION = array();

// 3. If it's desired to kill the session, also delete the session cookie.
// This is a pro-QA move to ensure the browser is completely "clean".
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// 4. Finally, destroy the session on the server side
session_destroy();

// 5. Redirect back to the login page
header("Location: login.php");
exit();
?>