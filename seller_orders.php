<?php

include("seller_auth.php");

$sellerId = $_SESSION['seller_id'];

$stmt = $conn->prepare("

SELECT

order_items.*,

orders.customer_name,

orders.phone,

orders.address,

orders.payment_method,

orders.payment_status,

orders.payment_reference,

orders.status,

orders.order_date,

orders.user_email

FROM order_items

INNER JOIN orders

ON order_items.order_id = orders.id

WHERE order_items.seller_id=?

ORDER BY orders.order_date DESC

");

$stmt->bind_param("i",$sellerId);

$stmt->execute();

$query = $stmt->get_result();

$totalRevenue = 0;

$totalProducts = 0;

while($item = $query->fetch_assoc()){

$totalRevenue +=
$item['price'] * $item['quantity'];

$totalProducts +=
$item['quantity'];

$orders[] = $item;

}

?>
<!DOCTYPE html>

<html>

<head>

<meta charset="UTF-8">

<title>Seller Orders</title>

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

.sidebar{

position:fixed;

width:250px;

height:100vh;

background:black;

padding:30px;

}

.logo{

font-size:30px;

font-weight:bold;

color:gold;

margin-bottom:50px;

}

.sidebar a{

display:block;

color:#ccc;

text-decoration:none;

margin-bottom:20px;

font-size:18px;

}

.sidebar a:hover{

color:gold;

}

.main{

margin-left:270px;

padding:40px;

}

.cards{

display:grid;

grid-template-columns:repeat(2,1fr);

gap:20px;

margin-bottom:40px;

}

.card{

background:#111;

padding:30px;

border-radius:15px;

text-align:center;

border:1px solid #222;

}

.card h1{

color:gold;

font-size:40px;

margin-bottom:15px;

}

.order{

background:#111;

padding:25px;

border-radius:15px;

margin-bottom:20px;

border:1px solid #222;

}

.order h2{

color:gold;

margin-bottom:15px;

}

.order p{

margin-bottom:10px;

line-height:1.8;

}

</style>

</head>

<body>


<div class="sidebar">

<div class="logo">

LuxuryVault

</div>

<a href="seller_dashboard.php">

Dashboard

</a>

<a href="manage_products.php">

My Products

</a>

<a href="seller_orders.php">

Orders

</a>

<a href="seller_profile.php">

Store Profile

</a>

<a href="logout.php">

Logout

</a>

</div>

<div class="main">

<h1 style="color:gold;margin-bottom:35px;">

Seller Orders

</h1>

<div class="cards">

<div class="card">

<h1>

TZS <?php echo number_format($totalRevenue); ?>

</h1>

<p>

Revenue

</p>

</div>

<div class="card">

<h1>

<?php echo $totalProducts; ?>

</h1>

<p>

Products Sold

</p>

</div>

</div>

<?php

if(!empty($orders)){

foreach($orders as $row){

?>

<div class="order">

<h2>

<?php echo htmlspecialchars($row['product_name']); ?>

</h2>

<p>

<strong>Customer:</strong>

<?php echo htmlspecialchars($row['customer_name']); ?>

</p>

<p>

<strong>Email:</strong>

<?php echo htmlspecialchars($row['user_email']); ?>

</p>

<p>

<strong>Phone:</strong>

<?php echo htmlspecialchars($row['phone']); ?>

</p>

<p>

<strong>Address:</strong>

<?php echo htmlspecialchars($row['address']); ?>

</p>

<p>

<strong>Quantity:</strong>

<?php echo $row['quantity']; ?>

</p>

<p>

<strong>Price:</strong>

TZS <?php echo number_format($row['price']); ?>

</p>

<p>

<strong>Total:</strong>

TZS <?php echo number_format(
$row['price'] * $row['quantity']
); ?>

</p>

<p>

<strong>Payment:</strong>

<?php echo htmlspecialchars($row['payment_method']); ?>

</p>

<p>

<strong>Payment Status:</strong>

<?php echo htmlspecialchars($row['payment_status']); ?>

</p>

<p>

<strong>Reference:</strong>

<?php echo htmlspecialchars($row['payment_reference']); ?>

</p>

<p>

<strong>Order Status:</strong>

<?php echo htmlspecialchars($row['status']); ?>

</p>

<p>

<strong>Date:</strong>

<?php echo htmlspecialchars($row['order_date']); ?>

</p>

</div>

<?php

}

}else{

?>

<div class="order">

<h2>

No Orders Yet

</h2>

<p>

When customers buy your products, they will appear here.

</p>

</div>

<?php

}

?>

</div>

</body>

</html>