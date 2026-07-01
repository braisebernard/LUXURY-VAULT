<?php

include("admin_auth.php");

// Validate Product ID
if(!isset($_GET['id']) || !is_numeric($_GET['id'])){
    header("Location: manage_products.php");
    exit();
}

$id = (int)$_GET['id'];

// Get product image before deleting
$stmt = $conn->prepare(
    "SELECT image
     FROM products
     WHERE id = ?"
);

$stmt->bind_param("i", $id);
$stmt->execute();

$result = $stmt->get_result();

if($result->num_rows == 0){
    header("Location: manage_products.php");
    exit();
}

$product = $result->fetch_assoc();

// Delete image file if it exists
if(
    !empty($product['image']) &&
    file_exists("images/".$product['image'])
){
    unlink("images/".$product['image']);
}

// Delete product
$stmt = $conn->prepare(
    "DELETE FROM products
     WHERE id = ?"
);

$stmt->bind_param("i", $id);

if($stmt->execute()){

    header("Location: manage_products.php?success=deleted");
    exit();

}else{

    die("Failed to delete product.");

}

?>