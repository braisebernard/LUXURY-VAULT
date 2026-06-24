<?php
session_start();
include("connect.php");

$id = $_POST['id'];
$status = $_POST['status'];

mysqli_query($conn,
"UPDATE orders
SET status='$status'
WHERE id='$id'");

header("Location: view_orders.php");
exit();
?>