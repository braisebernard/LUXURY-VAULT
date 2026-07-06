<?php

session_set_cookie_params([
    'httponly'=>true,
    'samesite'=>'Lax'
]);

session_start();
include("config/connect.php");

if(!isset($_SESSION['email'])){

    header("Location:login.php");
    exit();

}

$email=$_SESSION['email'];

$stmt=$conn->prepare(
"SELECT * FROM users
WHERE email=?");

$stmt->bind_param("s",$email);

$stmt->execute();

$result=$stmt->get_result();

if($result->num_rows!=1){

session_destroy();

header("Location:login.php");

exit();

}

$user=$result->fetch_assoc();

if($user['role']!="seller"){

die("Access Denied.");

}

if($user['status']!="approved"){

die("Seller account not approved.");

}

$_SESSION['seller_id']=$user['id'];
$_SESSION['shop_name']=$user['shop_name'];

?>