<?php

include("product_auth.php");

if(!$isAdmin){
    die("Access Denied.");
}

if(
    !isset($_GET['id']) ||
    !is_numeric($_GET['id'])
){
    die("Invalid Product.");
}

$id = (int)$_GET['id'];

$stmt = $conn->prepare("
UPDATE products
SET status='approved'
WHERE id=?
");

$stmt->bind_param("i",$id);
$stmt->execute();

header("Location: pending_products.php");
exit();

?>