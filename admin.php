<?php
include("connect.php");
include("admin_auth.php");

/*==========================================
DASHBOARD COUNTS
==========================================*/

$totalProducts = mysqli_num_rows(
mysqli_query($conn,"SELECT * FROM products"));

$totalOrders = mysqli_num_rows(
mysqli_query($conn,"SELECT * FROM orders"));

$totalUsers = mysqli_num_rows(
mysqli_query($conn,"SELECT * FROM users WHERE role='customer'"));

$totalSellers = mysqli_num_rows(
mysqli_query($conn,"SELECT * FROM users WHERE role='seller'"));

$pendingSellers = mysqli_num_rows(
mysqli_query($conn,"
SELECT * FROM users
WHERE role='seller'
AND status='pending'
"));

$approvedSellers = mysqli_num_rows(
mysqli_query($conn,"
SELECT * FROM users
WHERE role='seller'
AND status='approved'
"));

$revenueQuery = mysqli_query($conn,
"SELECT SUM(total) AS revenue FROM orders");

$revenueData=mysqli_fetch_assoc($revenueQuery);

$revenue=$revenueData['revenue'];

if($revenue==NULL){
$revenue=0;
}
?>

<!DOCTYPE html>
<html>
<head>
<title>LuxuryVault Admin</title>

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
flex:1;
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
}

.card h1{
color:gold;
font-size:40px;
}

.card p{
margin-top:15px;
color:#aaa;
}
.stats{
display:grid;
grid-template-columns:repeat(4,1fr);
gap:20px;
margin:30px 0;
}

.card{
background:#111;
padding:30px;
text-align:center;
border-radius:10px;
border:1px solid #222;
}

.card h2{
color:gold;
margin-bottom:10px;
}

</style>
</head>
<body>

<div class="sidebar">

<div class="logo">

LuxuryVault

</div>

<a href="admin.php">

Dashboard

</a>

<a href="seller_requests.php">

Seller Applications

</a>

<a href="add_product.php">

Add Product

</a>

<a href="manage_products.php">

Manage Products

</a>

<a href="view_orders.php">

Orders

</a>

<a href="view_users.php">

Customers

</a>

<a href="view_messages.php">

Messages

</a>

<a href="index.php">

Website

</a>

<a href="logout.php">

Logout

</a>

</div>

<div class="main">

<h1 class="title">Admin Dashboard</h1>
<div class="stats">

<div class="card">

<h2>

<?php echo $totalProducts; ?>

</h2>

<p>

Products

</p>

</div>

<div class="card">

<h2>

<?php echo $totalOrders; ?>

</h2>

<p>

Orders

</p>

</div>

<div class="card">

<h2>

<?php echo $totalUsers; ?>

</h2>

<p>

Customers

</p>

</div>

<div class="card">

<h2>

<?php echo $totalSellers; ?>

</h2>

<p>

Sellers

</p>

</div>

<div class="card">

<h2>

<?php echo $pendingSellers; ?>

</h2>

<p>

Pending Sellers

</p>

</div>

<div class="card">

<h2>

<?php echo $approvedSellers; ?>

</h2>

<p>

Approved Sellers

</p>

</div>

<div class="card">

<h2>

TZS <?php echo number_format($revenue); ?>

</h2>

<p>

Revenue

</p>

</div>

</div>



</body>
</html>