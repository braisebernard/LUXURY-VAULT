<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include("config/connect.php");

if(!isset($_SESSION['email'])){
    header("Location: login.php");
    exit();
}

$email = $_SESSION['email'];

$stmt = $conn->prepare("SELECT is_admin FROM users WHERE email=?");
$stmt->bind_param("s",$email);
$stmt->execute();

$result = $stmt->get_result();

$user = $result->fetch_assoc();

if(!$user || $user['is_admin'] != 1){
    header("Location:index.php");
    exit();
}
?>