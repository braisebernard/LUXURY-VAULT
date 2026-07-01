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

// Required fields
if(
    !isset($_POST['customer_name']) ||
    !isset($_POST['phone']) ||
    !isset($_POST['address']) ||
    !isset($_POST['payment_method']) ||
    !isset($_POST['payment_status']) ||
    !isset($_POST['payment_reference']) ||
    !isset($_POST['total'])
){
    header("Location:checkout.php");
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

// Allowed payment methods
$allowedPayments = [
    "M-Pesa",
    "Airtel Money",
    "Tigo Pesa",
    "Cash On Delivery"
];

if(!in_array($payment,$allowedPayments)){
    die("Invalid payment method.");
}

// Verify cart total from database
$stmt = $conn->prepare("
SELECT SUM(products.price * cart.quantity) AS total
FROM cart
INNER JOIN products
ON cart.product_id = products.id
WHERE cart.user_email=?
");

$stmt->bind_param("s",$email);
$stmt->execute();

$result = $stmt->get_result();
$data = $result->fetch_assoc();

$dbTotal = (float)$data['total'];

if($dbTotal<=0){
    die("Your cart is empty.");
}

// Use database total
$total = $dbTotal;

// Default order status
$orderStatus = "Processing";

// Transaction
$conn->begin_transaction();

try{

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

// Clear cart
$stmt = $conn->prepare(
"DELETE FROM cart
WHERE user_email=?"
);

$stmt->bind_param("s",$email);
$stmt->execute();

$conn->commit();

}catch(Exception $e){

$conn->rollback();

die("Order Failed : ".$e->getMessage());

}

?>

<!DOCTYPE html>
<html>

<head>

<title>Payment Successful</title>

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
width:600px;
background:#111;
padding:45px;
border-radius:15px;
border:1px solid #222;
text-align:center;
}

h1{
color:gold;
margin-bottom:20px;
}

.success{
font-size:70px;
margin-bottom:20px;
}

.info{
background:#1a1a1a;
padding:20px;
border-radius:10px;
margin:25px 0;
text-align:left;
line-height:2;
}

.info strong{
color:gold;
}

button{
padding:15px 30px;
background:gold;
border:none;
font-weight:bold;
border-radius:8px;
cursor:pointer;
margin:10px;
}

button:hover{
background:#d4af37;
}

</style>

</head>

<body>

<div class="box">

<div class="success">

✅

</div>

<h1>

Payment Successful

</h1>

<p>

Your payment has been processed successfully.

</p>

<div class="info">

<strong>Customer:</strong>

<?php echo htmlspecialchars($name); ?>

<br>

<strong>Payment Method:</strong>

<?php echo htmlspecialchars($payment); ?>

<br>

<strong>Payment Status:</strong>

<?php echo htmlspecialchars($paymentStatus); ?>

<br>

<strong>Reference:</strong>

<?php echo htmlspecialchars($paymentReference); ?>

<br>

<strong>Total Paid:</strong>

TZS <?php echo number_format($total); ?>

</div>

<a href="my_orders.php">

<button>

View My Orders

</button>

</a>

<a href="index.php">

<button>

Back To Homepage

</button>

</a>

</div>

</body>

</html>