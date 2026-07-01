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

// Secure Query
$stmt = $conn->prepare(
"SELECT *
FROM orders
WHERE user_email = ?
ORDER BY order_date DESC");

$stmt->bind_param("s",$email);
$stmt->execute();

$query = $stmt->get_result();

$totalOrders = $query->num_rows;

?>

<!DOCTYPE html>
<html>

<head>

<title>LuxuryVault | My Orders</title>

<style>

*{
margin:0;
padding:0;
box-sizing:border-box;
font-family:Poppins,sans-serif;
}

body{
background:#0b0b0b;
color:white;
}

.navbar{
background:black;
padding:20px 50px;
display:flex;
justify-content:space-between;
align-items:center;
border-bottom:1px solid #222;
}

.logo{
font-size:28px;
font-weight:bold;
color:gold;
}

.nav-links a{
color:white;
text-decoration:none;
margin-left:20px;
}

.nav-links a:hover{
color:gold;
}

.container{
width:90%;
max-width:1000px;
margin:40px auto;
}

h1{
text-align:center;
color:gold;
margin-bottom:30px;
font-size:40px;
}

.summary{
background:#111;
padding:20px;
border-radius:10px;
border:1px solid #222;
margin-bottom:30px;
font-size:20px;
}

.summary span{
color:gold;
font-weight:bold;
}

.order{
background:#111;
padding:25px;
margin-bottom:25px;
border-radius:15px;
border:1px solid #222;
}

.order h2{
color:gold;
margin-bottom:20px;
}

.order p{
margin:10px 0;
line-height:1.7;
}

.status{
color:gold;
font-weight:bold;
}

.payment{
color:#32CD32;
font-weight:bold;
}

.reference{
color:#4FC3F7;
font-family:monospace;
font-weight:bold;
}

.empty{
background:#111;
padding:50px;
text-align:center;
border-radius:15px;
border:1px solid #222;
font-size:20px;
color:#999;
}

.btn{
display:inline-block;
margin-top:25px;
padding:14px 28px;
background:gold;
color:black;
text-decoration:none;
font-weight:bold;
border-radius:8px;
}

.btn:hover{
background:#d4af37;
}

.back{
text-align:center;
margin-top:40px;
}

.back a{
display:inline-block;
padding:14px 28px;
background:#333;
color:white;
text-decoration:none;
border-radius:8px;
}

.back a:hover{
background:#555;
}

</style>

</head>

<body>

<div class="navbar">

<div class="logo">

LuxuryVault

</div>

<div class="nav-links">

<a href="index.php">Home</a>

<a href="products.php">Products</a>

<a href="cart.php">Cart</a>

<a href="logout.php">Logout</a>

</div>

</div>

<div class="container">

<h1>My Orders</h1>

<div class="summary">

Total Orders:

<span>

<?php echo $totalOrders; ?>

</span>

</div>

<?php

if($totalOrders > 0){

while($row = $query->fetch_assoc()){

?>

<div class="order">

<h2>

Order #<?php echo (int)$row['id']; ?>

</h2>

<p>

<strong>Customer:</strong>

<?php echo htmlspecialchars($row['customer_name']); ?>

</p>

<p>

<strong>Phone:</strong>

<?php echo htmlspecialchars($row['phone']); ?>

</p>

<p>

<strong>Delivery Address:</strong>

<?php echo htmlspecialchars($row['address']); ?>

</p>

<p>

<strong>Payment Method:</strong>

<?php echo htmlspecialchars($row['payment_method']); ?>

</p>

<p class="payment">

<strong>Payment Status:</strong>

<?php echo htmlspecialchars($row['payment_status']); ?>

</p>

<p class="reference">

<strong>Payment Reference:</strong>

<?php echo htmlspecialchars($row['payment_reference']); ?>

</p>

<p>

<strong>Total Paid:</strong>

TZS <?php echo number_format($row['total']); ?>

</p>

<p class="status">

<strong>Order Status:</strong>

<?php echo htmlspecialchars($row['status']); ?>

</p>

<p>

<strong>Order Date:</strong>

<?php echo htmlspecialchars($row['order_date']); ?>

</p>

</div>

<?php

}

}else{

?>

<div class="empty">

You have not placed any orders yet.

<br>

<a href="products.php" class="btn">

Shop Now

</a>

</div>

<?php

}

?>

<div class="back">

<a href="index.php">

← Back To Homepage

</a>

</div>

</div>

</body>

</html>