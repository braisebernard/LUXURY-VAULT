<?php

session_start();
include("connect.php");

if(!isset($_SESSION['email'])){
    header("Location:login.php");
    exit();
}

$email = $_SESSION['email'];

$productId = (int)$_POST['product_id'];
$rating = (int)$_POST['rating'];
$comment = trim($_POST['comment']);

if($rating < 1 || $rating > 5){
    die("Invalid rating.");
}

/* ==========================================
   VERIFIED PURCHASE CHECK
========================================== */

$stmt = $conn->prepare("

SELECT COUNT(*) total

FROM order_items

INNER JOIN orders

ON order_items.order_id = orders.id

WHERE

orders.user_email=?

AND order_items.product_id=?

");

$stmt->bind_param("si",$email,$productId);

$stmt->execute();

$purchase = $stmt->get_result()->fetch_assoc();

if($purchase['total'] == 0){

    die("You can only review products you have purchased.");

}

/* ==========================================
   CHECK IF REVIEW ALREADY EXISTS
========================================== */

$stmt = $conn->prepare("

SELECT id

FROM reviews

WHERE

product_id=?

AND user_email=?

");

$stmt->bind_param("is",$productId,$email);

$stmt->execute();

$existing = $stmt->get_result();

/* ==========================================
   UPDATE OR INSERT
========================================== */

if($existing->num_rows > 0){

    $review = $existing->fetch_assoc();

    $stmt = $conn->prepare("

    UPDATE reviews

    SET

    rating=?,
    comment=?,
    created_at=NOW()

    WHERE id=?

    ");

    $stmt->bind_param(

        "isi",

        $rating,
        $comment,
        $review['id']

    );

}else{

    $stmt = $conn->prepare("

    INSERT INTO reviews

    (

    product_id,
    user_email,
    rating,
    comment

    )

    VALUES

    (

    ?,?,?,?

    )

    ");

    $stmt->bind_param(

        "isis",

        $productId,
        $email,
        $rating,
        $comment

    );

}

$stmt->execute();

header("Location: product.php?id=".$productId);

exit();

?>