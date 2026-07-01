<?php
include("admin_auth.php");

// Validate Order ID
if(!isset($_GET['id']) || !is_numeric($_GET['id'])){
    header("Location:view_orders.php");
    exit();
}

$id = (int)$_GET['id'];

// Allowed statuses
$allowedStatus = [
    "Pending",
    "Processing",
    "Delivered",
    "Cancelled"
];

// Validate status
if(!isset($_GET['status']) || !in_array($_GET['status'], $allowedStatus)){
    header("Location:view_orders.php");
    exit();
}

$status = $_GET['status'];

// Check if order exists
$stmt = $conn->prepare(
    "SELECT id
     FROM orders
     WHERE id = ?"
);

$stmt->bind_param("i", $id);
$stmt->execute();

$result = $stmt->get_result();

if($result->num_rows == 0){
    header("Location:view_orders.php");
    exit();
}

// Update order securely
$stmt = $conn->prepare(
    "UPDATE orders
     SET status = ?
     WHERE id = ?"
);

$stmt->bind_param("si", $status, $id);

if($stmt->execute()){

    header("Location:view_orders.php?success=updated");
    exit();

}else{

    die("Failed to update order.");

}

?>