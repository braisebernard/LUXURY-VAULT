<?php
include("connect.php");
include("admin_auth.php");

$id = $_GET['id'];
$status = $_GET['status'];

mysqli_query($conn,
"UPDATE orders
SET status='$status'
WHERE id='$id'");

header("Location:view_orders.php");
exit();
?>