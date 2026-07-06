<?php

session_set_cookie_params([
    'httponly' => true,
    'samesite' => 'Lax'
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
    header("Location: cart.php");
    exit();
}

$product_id = (int)$_GET['id'];

// Check if item exists
$stmt = $conn->prepare(
    "SELECT quantity
     FROM cart
     WHERE user_email = ? AND product_id = ?"
);

$stmt->bind_param("si", $email, $product_id);
$stmt->execute();

$result = $stmt->get_result();

if($result->num_rows == 0){
    header("Location: cart.php");
    exit();
}

$row = $result->fetch_assoc();

// If quantity is greater than 1, decrease it
if($row['quantity'] > 1){

    $stmt = $conn->prepare(
        "UPDATE cart
         SET quantity = quantity - 1
         WHERE user_email = ? AND product_id = ?"
    );

    $stmt->bind_param("si", $email, $product_id);
    $stmt->execute();

}else{

    // Remove item when quantity reaches 1
    $stmt = $conn->prepare(
        "DELETE FROM cart
         WHERE user_email = ? AND product_id = ?"
    );

    $stmt->bind_param("si", $email, $product_id);
    $stmt->execute();
}

header("Location: cart.php");
exit();

?>