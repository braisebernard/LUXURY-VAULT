<?php
session_start();
include("connect.php");

$email = $_SESSION['email'];
$product_id = $_GET['id'];

mysqli_query($conn,
"UPDATE cart
SET quantity = quantity - 1
WHERE user_email='$email'
AND product_id='$product_id'
AND quantity > 1");

header("Location: cart.php");
exit();
?>