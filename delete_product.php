<?php

include("product_auth.php");

/* ==========================================
   VALIDATE PRODUCT ID
========================================== */

if(
    !isset($_GET['id']) ||
    !is_numeric($_GET['id'])
){
    die("Invalid Product.");
}

$productId = (int)$_GET['id'];

/* ==========================================
   LOAD PRODUCT
========================================== */

if($isAdmin){

    $stmt = $conn->prepare("
    SELECT image
    FROM products
    WHERE id=?
    ");

    $stmt->bind_param("i",$productId);

}else{

    $sellerId = $_SESSION['user_id'];

    $stmt = $conn->prepare("
    SELECT image
    FROM products
    WHERE id=?
    AND seller_id=?
    ");

    $stmt->bind_param(
        "ii",
        $productId,
        $sellerId
    );

}

$stmt->execute();

$result = $stmt->get_result();

if($result->num_rows==0){

    die("Access Denied.");

}

$product = $result->fetch_assoc();

/* ==========================================
   DELETE IMAGE
========================================== */

$imagePath = "images/".$product['image'];

if(
    !empty($product['image']) &&
    file_exists($imagePath)
){
    unlink($imagePath);
}

/* ==========================================
   DELETE PRODUCT
========================================== */

if($isAdmin){

    $stmt = $conn->prepare("
    DELETE FROM products
    WHERE id=?
    ");

    $stmt->bind_param(
        "i",
        $productId
    );

}else{

    $sellerId = $_SESSION['user_id'];

    $stmt = $conn->prepare("
    DELETE FROM products
    WHERE id=?
    AND seller_id=?
    ");

    $stmt->bind_param(
        "ii",
        $productId,
        $sellerId
    );

}

if($stmt->execute()){

    header("Location: manage_products.php");
    exit();

}else{

    die("Failed to delete product.");

}

?>