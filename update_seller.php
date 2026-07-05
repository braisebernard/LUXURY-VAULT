<?php

include("connect.php");
include("admin_auth.php");

if(
!isset($_GET['id']) ||
!isset($_GET['status'])
){
header("Location:seller_requests.php");
exit();
}

$id=(int)$_GET['id'];

$status=$_GET['status'];

if(
$status!="approved" &&
$status!="rejected"
){
die("Invalid Status");
}

$stmt=$conn->prepare(
"UPDATE users
SET status=?
WHERE id=?");

$stmt->bind_param(
"si",
$status,
$id
);

$stmt->execute();

header("Location:seller_requests.php");

exit();

?>