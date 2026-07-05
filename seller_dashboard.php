<?php

include("seller_auth.php");

$sellerId=$_SESSION['seller_id'];

$productQuery=mysqli_query(
$conn,
"SELECT COUNT(*) total
FROM products
WHERE seller_id='$sellerId'"
);

$productData=mysqli_fetch_assoc($productQuery);

$totalProducts=$productData['total'];

$pendingQuery=mysqli_query(
$conn,
"SELECT COUNT(*) total
FROM products
WHERE seller_id='$sellerId'
AND status='pending'"
);

$pendingData=mysqli_fetch_assoc($pendingQuery);

$pendingProducts=$pendingData['total'];

$approvedQuery=mysqli_query(
$conn,
"SELECT COUNT(*) total
FROM products
WHERE seller_id='$sellerId'
AND status='approved'"
);

$approvedData=mysqli_fetch_assoc($approvedQuery);

$approvedProducts=$approvedData['total'];

?>

<!DOCTYPE html>

<html>

<head>

<title>Seller Dashboard</title>

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

margin-bottom:25px;

font-size:18px;

}

.sidebar a:hover{

color:gold;

}

.main{

margin-left:270px;

padding:40px;

}

.title{

color:gold;

margin-bottom:40px;

}

.cards{

display:grid;

grid-template-columns:repeat(auto-fit,minmax(250px,1fr));

gap:25px;

}

.card{

background:#111;

border:1px solid #222;

padding:30px;

border-radius:15px;

text-align:center;

}

.card h1{

font-size:45px;

color:gold;

margin-bottom:15px;

}

.card p{

color:#aaa;

font-size:18px;

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

<a href="add_product.php">

Add Product

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

<h1 class="title">

Welcome,

<?php echo $_SESSION['shop_name']; ?>

</h1>

<div class="cards">

<div class="card">

<h1>

<?php echo $totalProducts; ?>

</h1>

<p>

My Products

</p>

</div>

<div class="card">

<h1>

<?php echo $pendingProducts; ?>

</h1>

<p>

Pending Approval

</p>

</div>

<div class="card">

<h1>

<?php echo $approvedProducts; ?>

</h1>

<p>

Approved Products

</p>

</div>

</div>

</div>

</body>

</html>