<?php
session_start();

// Destroy all session variables
session_unset();

// Destroy the session
session_destroy();

// Destroy the session cookie by setting its expiration time to the past
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 3600, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
}

// Clear the browser cache to prevent back button
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

// Redirect to the login page
header("Location: login.php");
exit;
?>
