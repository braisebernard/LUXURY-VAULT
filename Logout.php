<?php

session_set_cookie_params([
    'httponly' => true,
    'samesite' => 'Lax'
]);

session_start();
// Remove all session variables
session_unset();

// Destroy the session
session_destroy();

// Redirect user to homepage
header("Location: index.php");
exit();

?>