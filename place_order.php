<?php
session_start();
include("connect.php");

if(!isset($_SESSION['email'])){
    header("Location: login.php");
    exit();
}

$email = $_SESSION['email'];

$name = $_POST['customer_name'];
$phone = $_POST['phone'];
$address = $_POST['address'];
$payment = $_POST['payment_method'];
$total = $_POST['total'];

$sql = "INSERT INTO orders
(customer_name, phone, address, payment_method, total, user_email)

VALUES
('$name', '$phone', '$address', '$payment', '$total', '$email')";

$result = mysqli_query($conn, $sql);

if($result){

    mysqli_query($conn,
    "DELETE FROM cart WHERE user_email='$email'");

    echo "<script>
    alert('Order placed successfully');
    window.location='my_orders.php';
    </script>";

}else{

    die("Database Error: ".mysqli_error($conn));

}
?>