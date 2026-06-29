<?php
session_set_cookie_params([
    'httponly' => true,
    'samesite' => 'Lax'
]);

session_start();
include("connect.php");

$email = $_SESSION['email'];
$product_id = $_GET['id'];

mysqli_query($conn,
"DELETE FROM cart
WHERE user_email='$email'
AND product_id='$product_id'");

header("Location: cart.php");
exit();
?>