<?php
//setcookie("__AE_TOKEN__", "", time() - 1);

foreach ($_COOKIE as $cookie_name => $cookie_val)
{
    setcookie($cookie_name, "", 0);
}

$_SESSION = array();

// If it's desired to kill the session, also delete the session cookie.
// Note: This will destroy the session, and not just the session data!
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Finally, destroy the session.
session_destroy();

$last_page = 'index.php?no-referrer';
if (isset($_SERVER['HTTP_REFERRER']))
    $last_page = $_SERVER['HTTP_REFERRER'];
header('Location: ' . $last_page);
exit;