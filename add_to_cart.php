<?php
session_set_cookie_params([
    'httponly' => true,
    'samesite' => 'Lax'
]);

session_start();
include("connect.php");

if(!isset($_SESSION['email'])){
    header("Location: login.php");
    exit();
}

$email = $_SESSION['email'];
$product_id = $_GET['id'];

// Check if already exists
$check = mysqli_query($conn,
"SELECT * FROM cart
WHERE user_email='$email'
AND product_id='$product_id'");

if(mysqli_num_rows($check) > 0){

    mysqli_query($conn,
    "UPDATE cart
    SET quantity = quantity + 1
    WHERE user_email='$email'
    AND product_id='$product_id'");

}else{

    mysqli_query($conn,
    "INSERT INTO cart(user_email,product_id)
    VALUES('$email','$product_id')");
}

header("Location: cart.php");
exit();
?>