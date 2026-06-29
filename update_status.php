<?php
include("connect.php");
include("admin_auth.php");

$id = $_POST['id'];
$status = $_POST['status'];

mysqli_query($conn,
"UPDATE orders
SET status='$status'
WHERE id='$id'");

header("Location: view_orders.php");
exit();
?>