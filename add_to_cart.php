<?php

session_set_cookie_params([
    'httponly' => true,
    'samesite'  => 'Lax'
]);

session_start();
include("config/connect.php");

// User must be logged in
if(!isset($_SESSION['email'])){
    header("Location: login.php");
    exit();
}

$email = $_SESSION['email'];

// Validate Product ID
if(!isset($_GET['id']) || !is_numeric($_GET['id'])){
    header("Location: products.php");
    exit();
}

$product_id = (int)$_GET['id'];

/* Verify product exists */
$stmt = $conn->prepare("SELECT id FROM products WHERE id = ?");
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();

if($result->num_rows == 0){
    header("Location: products.php");
    exit();
}

/* Check if product already exists in cart */
$stmt = $conn->prepare(
    "SELECT quantity FROM cart
     WHERE user_email = ? AND product_id = ?"
);

$stmt->bind_param("si", $email, $product_id);
$stmt->execute();

$result = $stmt->get_result();

if($result->num_rows > 0){

    // Increase quantity
    $stmt = $conn->prepare(
        "UPDATE cart
         SET quantity = quantity + 1
         WHERE user_email = ? AND product_id = ?"
    );

    $stmt->bind_param("si", $email, $product_id);
    $stmt->execute();

}else{

    // Add new item
    $quantity = 1;

    $stmt = $conn->prepare(
        "INSERT INTO cart(user_email, product_id, quantity)
         VALUES(?, ?, ?)"
    );

    $stmt->bind_param("sii", $email, $product_id, $quantity);
    $stmt->execute();
}

// Redirect back to cart
header("Location: cart.php");
exit();

?>