<?php

session_start();
include("config/connect.php");

if(!isset($_SESSION['email'])){
    header("Location:login.php");
    exit();
}

if(
    !isset($_GET['id']) ||
    !is_numeric($_GET['id'])
){
    header("Location:wishlist.php");
    exit();
}

$email = $_SESSION['email'];
$productId = (int)$_GET['id'];

$stmt = $conn->prepare("

DELETE FROM wishlist

WHERE

user_email=?

AND product_id=?

");

$stmt->bind_param("si",$email,$productId);

$stmt->execute();

header("Location:wishlist.php");

exit();

?>