<?php

session_set_cookie_params([
    'httponly' => true,
    'samesite' => 'Lax'
]);

session_start();
include("connect.php");

// User must be logged in
if(!isset($_SESSION['email'])){
    header("Location: login.php");
    exit();
}

$email = $_SESSION['email'];

// Check required fields
if(
    !isset($_POST['customer_name']) ||
    !isset($_POST['phone']) ||
    !isset($_POST['address']) ||
    !isset($_POST['payment_method']) ||
    !isset($_POST['payment_status']) ||
    !isset($_POST['payment_reference']) ||
    !isset($_POST['total'])
){
    header("Location: checkout.php");
    exit();
}

// Sanitize Inputs
$name = trim($_POST['customer_name']);
$phone = trim($_POST['phone']);
$address = trim($_POST['address']);
$payment = trim($_POST['payment_method']);
$paymentStatus = trim($_POST['payment_status']);
$paymentReference = trim($_POST['payment_reference']);
$total = (float)$_POST['total'];

// Validate payment method
$allowedPayments = [
    "M-Pesa",
    "Airtel Money",
    "Tigo Pesa",
    "Cash On Delivery"
];

if(!in_array($payment, $allowedPayments)){
    die("Invalid payment method.");
}

// Verify total from database
$stmt = $conn->prepare("
SELECT SUM(products.price * cart.quantity) AS total
FROM cart
INNER JOIN products
ON cart.product_id = products.id
WHERE cart.user_email = ?
");

$stmt->bind_param("s", $email);
$stmt->execute();

$result = $stmt->get_result();
$data = $result->fetch_assoc();

$dbTotal = (float)$data['total'];

if($dbTotal <= 0){
    die("Your cart is empty.");
}

// Always trust database total
$total = $dbTotal;

// Default Order Status
$orderStatus = "Processing";

// Begin Transaction
$conn->begin_transaction();

try{

    // Insert Order
    $stmt = $conn->prepare("
    INSERT INTO orders
    (
        customer_name,
        phone,
        address,
        payment_method,
        payment_status,
        payment_reference,
        total,
        status,
        user_email
    )
    VALUES
    (
        ?,?,?,?,?,?,?,?,?
    )
    ");

    $stmt->bind_param(
        "ssssssdss",
        $name,
        $phone,
        $address,
        $payment,
        $paymentStatus,
        $paymentReference,
        $total,
        $orderStatus,
        $email
    );

    $stmt->execute();

    // Clear Cart
    $stmt = $conn->prepare(
        "DELETE FROM cart WHERE user_email=?"
    );

    $stmt->bind_param("s",$email);
    $stmt->execute();

    // Commit Transaction
    $conn->commit();

    // Redirect to Email Sandbox
    header(
        "Location: send_email.php?" .
        "customer=" . urlencode($name) .
        "&payment=" . urlencode($payment) .
        "&payment_status=" . urlencode($paymentStatus) .
        "&reference=" . urlencode($paymentReference) .
        "&total=" . urlencode($total) .
        "&status=" . urlencode($orderStatus)
    );

    exit();

}catch(Exception $e){

    $conn->rollback();

    die("Order Failed: ".$e->getMessage());

}

?>