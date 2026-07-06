<?php

include("seller_auth.php");

$sellerId = $_SESSION['seller_id'];

/* ===========================
   TOTAL PRODUCTS
=========================== */
$stmt = $conn->prepare("
SELECT COUNT(*) total
FROM products
WHERE seller_id=?
");

$stmt->bind_param("i",$sellerId);
$stmt->execute();

$productData = $stmt->get_result()->fetch_assoc();

$totalProducts = $productData['total'];

/* ===========================
   PENDING PRODUCTS
=========================== */
$stmt = $conn->prepare("
SELECT COUNT(*) total
FROM products
WHERE seller_id=?
AND status='pending'
");

$stmt->bind_param("i",$sellerId);
$stmt->execute();

$pendingData = $stmt->get_result()->fetch_assoc();

$pendingProducts = $pendingData['total'];

/* ===========================
   APPROVED PRODUCTS
=========================== */

$stmt = $conn->prepare("
SELECT COUNT(*) total
FROM products
WHERE seller_id=?
AND status='approved'
");

$stmt->bind_param("i",$sellerId);
$stmt->execute();

$approvedData = $stmt->get_result()->fetch_assoc();

$approvedProducts = $approvedData['total'];

/* ===========================
   SELLER ORDERS
=========================== */

$stmt = $conn->prepare("
SELECT
COUNT(*) totalOrders,
SUM(price*quantity) revenue
FROM order_items
WHERE seller_id=?
");

$stmt->bind_param("i",$sellerId);
$stmt->execute();

$orderStats = $stmt->get_result()->fetch_assoc();

$totalOrders = $orderStats['totalOrders'] ?? 0;

$totalRevenue = $orderStats['revenue'] ?? 0;

/* ===========================
   LOW STOCK
=========================== */

$stmt = $conn->prepare("
SELECT *
FROM products
WHERE seller_id=?
AND quantity<=5
ORDER BY quantity ASC
LIMIT 5
");

$stmt->bind_param("i",$sellerId);
$stmt->execute();

$lowStock = $stmt->get_result();

/* ===========================
   TOP PRODUCTS
=========================== */

$stmt = $conn->prepare("
SELECT
product_name,
SUM(quantity) AS sold
FROM order_items
WHERE seller_id=?
GROUP BY product_name
ORDER BY sold DESC
LIMIT 5
");

if(!$stmt){
    die("SQL Error: ".$conn->error);
}

$stmt->bind_param("i",$sellerId);
$stmt->execute();

$topProducts = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
content="width=device-width, initial-scale=1.0">

<title>Seller Dashboard</title>

<style>

*{
margin:0;
padding:0;
box-sizing:border-box;
font-family:Poppins,sans-serif;
}

body{
display:flex;
background:#0b0b0b;
color:white;
}

/* Sidebar */

.sidebar{
width:250px;
min-height:100vh;
background:black;
padding:30px;
border-right:1px solid #222;
}

.logo{
font-size:30px;
font-weight:bold;
color:gold;
margin-bottom:45px;
}

.sidebar a{
display:block;
color:#ccc;
text-decoration:none;
margin-bottom:20px;
font-size:18px;
transition:.3s;
}

.sidebar a:hover{
color:gold;
}

/* Main */

.main{
flex:1;
padding:40px;
}

.title{
color:gold;
margin-bottom:35px;
}

/* Statistic Cards */

.cards{

display:grid;

grid-template-columns:repeat(auto-fit,minmax(220px,1fr));

gap:20px;

margin-bottom:40px;

}

.card{

background:#111;

border:1px solid #222;

border-radius:15px;

padding:30px;

text-align:center;

}

.card h2{

font-size:38px;

color:gold;

margin-bottom:10px;

}

.card p{

color:#bbb;

font-size:18px;

}

/* Panels */

.panel{

background:#111;

border:1px solid #222;

border-radius:15px;

padding:25px;

margin-bottom:30px;

}

.panel h2{

color:gold;

margin-bottom:20px;

}

table{

width:100%;

border-collapse:collapse;

}

th{

background:#1b1b1b;

padding:15px;

text-align:left;

color:gold;

}

td{

padding:15px;

border-bottom:1px solid #222;

}

.low{

color:#ff5252;

font-weight:bold;

}

.good{

color:#4CAF50;

font-weight:bold;

}

</style>

</head>

<body>

<div class="sidebar">

<div class="logo">

LuxuryVault

</div>

<a href="seller_dashboard.php">Dashboard</a>

<a href="add_product.php">Add Product</a>

<a href="manage_products.php">My Products</a>

<a href="seller_orders.php">Orders</a>

<a href="seller_profile.php">Store Profile</a>

<a href="index.php">Marketplace</a>

<a href="logout.php">Logout</a>

</div>
<div class="main">

<h1 class="title">

Seller Dashboard

</h1>

<div class="cards">

<div class="card">

<h2>

<?php echo $totalProducts; ?>

</h2>

<p>Total Products</p>

</div>

<div class="card">

<h2>

<?php echo $approvedProducts; ?>

</h2>

<p>Approved Products</p>

</div>

<div class="card">

<h2>

<?php echo $pendingProducts; ?>

</h2>

<p>Pending Approval</p>

</div>

<div class="card">

<h2>

<?php echo $totalOrders; ?>

</h2>

<p>Total Orders</p>

</div>

<div class="card">

<h2>

TZS <?php echo number_format($totalRevenue); ?>

</h2>

<p>Total Revenue</p>

</div>

</div>

<!-- LOW STOCK -->

<div class="panel">

<h2>

Low Stock Products

</h2>

<?php if($lowStock->num_rows>0){ ?>

<table>

<tr>

<th>Product</th>

<th>Stock</th>

<th>Status</th>

</tr>

<?php while($row=$lowStock->fetch_assoc()){ ?>

<tr>

<td>

<?php echo htmlspecialchars($row['name']); ?>

</td>

<td>

<?php echo (int)$row['quantity']; ?>

</td>

<td>

<?php

if($row['quantity']==0){

echo "<span class='low'>Out Of Stock</span>";

}elseif($row['quantity']<=5){

echo "<span class='low'>Low Stock</span>";

}else{

echo "<span class='good'>In Stock</span>";

}

?>

</td>

</tr>

<?php } ?>

</table>

<?php }else{ ?>

<p style="color:#999;">

All your products have sufficient stock.

</p>

<?php } ?>

</div>

<!-- TOP SELLING PRODUCTS -->

<div class="panel">

<h2>

Top Selling Products

</h2>

<?php if($topProducts->num_rows>0){ ?>

<table>

<tr>

<th>Product</th>

<th>Units Sold</th>

</tr>

<?php while($row=$topProducts->fetch_assoc()){ ?>

<tr>

<td>

<?php echo htmlspecialchars($row['product_name']); ?>

</td>

<td>

<?php echo (int)$row['sold']; ?>

</td>

</tr>

<?php } ?>

</table>

<?php }else{ ?>

<p style="color:#999;">

No sales have been made yet.

</p>

<?php } ?>

</div>

<!-- QUICK ACTIONS -->

<div class="panel">

<h2>

Quick Actions

</h2>

<div style="display:flex;gap:15px;flex-wrap:wrap;">

<a href="add_product.php">

<button style="padding:15px 25px;background:gold;border:none;border-radius:8px;font-weight:bold;cursor:pointer;">

Add Product

</button>

</a>

<a href="manage_products.php">

<button style="padding:15px 25px;background:gold;border:none;border-radius:8px;font-weight:bold;cursor:pointer;">

Manage Products

</button>

</a>

<a href="seller_orders.php">

<button style="padding:15px 25px;background:gold;border:none;border-radius:8px;font-weight:bold;cursor:pointer;">

View Orders

</button>

</a>

<a href="seller_profile.php">

<button style="padding:15px 25px;background:gold;border:none;border-radius:8px;font-weight:bold;cursor:pointer;">

Store Profile

</button>

</a>

</div>

</div>

</div>

</body>

</html>
