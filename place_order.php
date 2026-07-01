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
    !isset($_POST['total'])
){
    header("Location: checkout.php");
    exit();
}

// Sanitize input
$name = trim($_POST['customer_name']);
$phone = trim($_POST['phone']);
$address = trim($_POST['address']);
$payment = trim($_POST['payment_method']);
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

// Verify cart total from database
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

// Ignore posted total and use database total
$total = $dbTotal;

// Begin transaction
$conn->begin_transaction();

try{

    // Insert order
    $stmt = $conn->prepare("
    INSERT INTO orders
    (
        customer_name,
        phone,
        address,
        payment_method,
        total,
        user_email
    )
    VALUES(?,?,?,?,?,?)
    ");

    $stmt->bind_param(
        "ssssds",
        $name,
        $phone,
        $address,
        $payment,
        $total,
        $email
    );

    $stmt->execute();

    // Clear cart
    $stmt = $conn->prepare(
        "DELETE FROM cart WHERE user_email=?"
    );

    $stmt->bind_param("s", $email);
    $stmt->execute();

    // Commit changes
    $conn->commit();

}catch(Exception $e){

    $conn->rollback();

    die("Order Failed: ".$e->getMessage());

}

?>

<!DOCTYPE html>
<html>

<head>

<title>Order Successful</title>

<style>

*{
margin:0;
padding:0;
box-sizing:border-box;
font-family:Poppins,sans-serif;
}

body{
background:#0b0b0b;
display:flex;
justify-content:center;
align-items:center;
height:100vh;
color:white;
}

.box{
background:#111;
padding:50px;
border-radius:15px;
text-align:center;
border:1px solid #222;
width:500px;
}

h1{
color:gold;
margin-bottom:20px;
}

p{
margin-bottom:30px;
font-size:18px;
color:#ccc;
}

button{
padding:15px 30px;
background:gold;
color:black;
border:none;
border-radius:8px;
cursor:pointer;
font-weight:bold;
margin:10px;
}

button:hover{
background:#d4af37;
}

</style>

</head>

<body>

<div class="box">

<h1>Order Placed Successfully 🎉</h1>

<p>
Thank you for shopping with LuxuryVault.
Your order has been received successfully.
</p>

<a href="my_orders.php">
<button>View My Orders</button>
</a>

<a href="index.php">
<button>Go To Homepage</button>
</a>

</div>

</body>

</html>