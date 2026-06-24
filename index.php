<?php
session_start();
include("connect.php");
?>

<!DOCTYPE html>
<html>
<head>
<title>LuxuryVault</title>

<style>

body{
margin:0;
font-family:Poppins,sans-serif;
background:#0b0b0b;
color:white;
}

.navbar{
display:flex;
justify-content:space-between;
padding:20px 50px;
background:black;
}

.logo{
font-size:30px;
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

.hero{
text-align:center;
padding:120px 20px;
}

.hero h1{
font-size:60px;
color:gold;
margin-bottom:20px;
}

.hero p{
font-size:22px;
color:#bbb;
}

.hero-btn{
display:inline-block;
margin-top:30px;
padding:15px 35px;
background:gold;
color:black;
font-weight:bold;
text-decoration:none;
border-radius:5px;
}

.hero-btn:hover{
background:#d4af37;
}

.categories{
padding:60px;
display:grid;
grid-template-columns:repeat(4,1fr);
gap:20px;
}

.category{
background:#111;
padding:30px;
text-align:center;
border-radius:10px;
}

.category h2{
color:gold;
}

.why{
padding:60px;
text-align:center;
}

.why h1{
color:gold;
}

.why ul{
list-style:none;
padding:0;
}

.why li{
padding:10px;
font-size:18px;
}

.footer{
background:black;
padding:20px;
text-align:center;
color:#888;
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

<?php
if(isset($_SESSION['email'])){

    $email = $_SESSION['email'];

    $adminQuery = mysqli_query($conn,
    "SELECT * FROM users WHERE email='$email'");

    $adminData = mysqli_fetch_assoc($adminQuery);

    if($adminData['is_admin'] == 1){
?>
        <a href="admin.php">Admin</a>
<?php
    }
}
?>

<?php if(isset($_SESSION['email'])){ ?>

<a href="cart.php">Cart</a>
<a href="my_orders.php">My Orders</a>
<a href="logout.php">Logout</a>

<?php } else { ?>

<a href="login.php">Login</a>
<a href="register.php">Register</a>

<?php } ?>


</div>

</div>

<div class="hero">

<h1>Own Timeless Luxury</h1>

<p>
Discover authentic luxury watches, fragrances,
jewelry and designer accessories.
</p>

<a href="products.php" class="hero-btn">
Shop Collection
</a>

</div>

<div class="categories">

<div class="category">
<h2>⌚ Watches</h2>
<p>Swiss luxury watches</p>
</div>

<div class="category">
<h2>👜 Bags</h2>
<p>Designer handbags</p>
</div>

<div class="category">
<h2>💎 Jewelry</h2>
<p>Premium accessories</p>
</div>

<div class="category">
<h2>🌹 Fragrances</h2>
<p>Luxury perfumes</p>
</div>

</div>

<div class="why">

<h1>Why Choose LuxuryVault?</h1>

<ul>
<li>✓ Authentic Luxury Products</li>
<li>✓ Secure Transactions</li>
<li>✓ Fast Delivery</li>
<li>✓ Premium Customer Support</li>
</ul>

</div>

<div class="footer">
© 2026 LuxuryVault
</div>

</body>
</html>