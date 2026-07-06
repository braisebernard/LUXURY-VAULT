<?php

session_set_cookie_params([
    'httponly' => true,
    'samesite' => 'Lax'
]);

session_start();

include("config/connect.php");

if(!isset($_SESSION['email'])){

    header("Location: login.php");
    exit();

}

$email = $_SESSION['email'];

$stmt = $conn->prepare("
SELECT *
FROM users
WHERE email=?
");

$stmt->bind_param("s",$email);
$stmt->execute();

$result = $stmt->get_result();

if($result->num_rows != 1){

    session_destroy();

    header("Location: login.php");
    exit();

}

$user = $result->fetch_assoc();

$_SESSION['user_id'] = $user['id'];
$_SESSION['role'] = $user['role'];
$_SESSION['status'] = $user['status'];
$_SESSION['shop_name'] = $user['shop_name'];

$isAdmin = false;
$isSeller = false;

if($user['role']=="admin"){

    $isAdmin = true;

}elseif($user['role']=="seller"){

    if($user['status']!="approved"){

        die("Your seller account has not yet been approved.");

    }

    $isSeller = true;

}else{

    die("Access Denied.");

}
?>