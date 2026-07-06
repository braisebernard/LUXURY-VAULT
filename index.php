<?php
session_set_cookie_params([
'httponly'=>true,
'samesite'=>'Lax'
]);

session_start();
include("config/connect.php");

/* FEATURED PRODUCTS */

$featured=$conn->query("
SELECT
products.*,
users.shop_name
FROM products
LEFT JOIN users
ON products.seller_id=users.id
WHERE products.status='approved'
ORDER BY RAND()
LIMIT 8
");

/* LATEST PRODUCTS */

$latest=$conn->query("
SELECT
products.*,
users.shop_name
FROM products
LEFT JOIN users
ON products.seller_id=users.id
WHERE products.status='approved'
ORDER BY products.id DESC
LIMIT 8
");

/* FEATURED STORES */

$stores=$conn->query("
SELECT
id,
shop_name,
shop_logo
FROM users
WHERE role='seller'
AND status='approved'
LIMIT 6
");

$isAdmin=false;

if(isset($_SESSION['email'])){

$stmt=$conn->prepare("
SELECT role
FROM users
WHERE email=?
");

$stmt->bind_param("s",$_SESSION['email']);
$stmt->execute();

$user=$stmt->get_result()->fetch_assoc();

if($user && $user['role']=="admin"){
$isAdmin=true;
}

}
?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta
name="viewport"
content="width=device-width,initial-scale=1.0">

<title>LuxuryVault Marketplace</title>

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

a{
text-decoration:none;
}

.navbar{
display:flex;
justify-content:space-between;
align-items:center;
padding:20px 50px;
background:black;
border-bottom:1px solid #222;
position:sticky;
top:0;
z-index:1000;
}

.logo{
font-size:32px;
font-weight:bold;
color:gold;
}

.nav-links a{
color:white;
margin-left:22px;
transition:.3s;
}

.nav-links a:hover{
color:gold;
}

.hero{
height:85vh;
display:flex;
justify-content:center;
align-items:center;
text-align:center;
padding:20px;
background:
linear-gradient(rgba(0,0,0,.72),rgba(0,0,0,.82)),
url('images/luxury-banner.jpg');
background-size:cover;
background-position:center;
}

.hero-content{
max-width:800px;
}

.hero h1{
font-size:68px;
color:gold;
margin-bottom:20px;
}

.hero p{
font-size:22px;
color:#ddd;
line-height:1.8;
margin-bottom:40px;
}

.hero-btn{
display:inline-block;
padding:16px 36px;
background:gold;
color:black;
font-weight:bold;
border-radius:8px;
margin:8px;
transition:.3s;
}

.hero-btn:hover{
background:#d4af37;
transform:translateY(-2px);
}

.section{
width:90%;
margin:70px auto;
}

.section-title{
font-size:40px;
color:gold;
margin-bottom:30px;
}

.grid{
display:grid;
grid-template-columns:repeat(auto-fit,minmax(260px,1fr));
gap:25px;
}

.card{
background:#111;
border:1px solid #222;
border-radius:15px;
overflow:hidden;
transition:.3s;
}

.card:hover{
transform:translateY(-6px);
box-shadow:0 10px 25px rgba(255,215,0,.15);
}

.card img{
width:100%;
height:260px;
object-fit:cover;
}

.card-body{
padding:20px;
}

.price{
font-size:24px;
font-weight:bold;
margin:15px 0;
}

.footer{
background:black;
padding:35px;
text-align:center;
color:#777;
margin-top:70px;
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

<?php if($isAdmin){ ?>

<a href="admin.php">Admin</a>

<?php } ?>

<?php if(isset($_SESSION['email'])){ ?>

<a href="wishlist.php">Wishlist</a>

<a href="cart.php">Cart</a>

<a href="my_orders.php">My Orders</a>

<a href="logout.php">Logout</a>

<?php }else{ ?>

<a href="login.php">Login</a>

<a href="register.php">Register</a>

<?php } ?>

</div>

</div>

<div class="hero">

<div class="hero-content">

<h1>

Luxury Beyond Imagination

</h1>

<p>

Discover authentic luxury watches, fashion,
designer bags, jewelry and premium fragrances
from trusted sellers across LuxuryVault.

</p>

<a
href="products.php"
class="hero-btn">

Shop Collection

</a>

<a
href="#featured"
class="hero-btn"
style="background:#222;color:white;">

Explore

</a>

</div>

</div>
<!-- SEARCH -->

<div class="section">

<form
action="products.php"
method="GET"
style="
display:flex;
gap:15px;
flex-wrap:wrap;
">

<input
type="text"
name="search"
placeholder="Search luxury products..."
style="
flex:1;
padding:18px;
background:#111;
border:1px solid #333;
border-radius:10px;
color:white;
font-size:17px;
">

<button
style="
padding:18px 35px;
background:gold;
border:none;
border-radius:10px;
font-weight:bold;
cursor:pointer;
">

Search

</button>

</form>

</div>

<!-- CATEGORIES -->

<div class="section">

<h2 class="section-title">

Shop By Category

</h2>

<div class="grid">

<div class="card">

<div class="card-body" style="text-align:center;">

<h1>⌚</h1>

<h2 style="color:gold;margin:15px 0;">

Luxury Watches

</h2>

<p style="color:#aaa;">

Premium Swiss & designer watches.

</p>

</div>

</div>

<div class="card">

<div class="card-body" style="text-align:center;">

<h1>👜</h1>

<h2 style="color:gold;margin:15px 0;">

Designer Bags

</h2>

<p style="color:#aaa;">

Luxury handbags from trusted sellers.

</p>

</div>

</div>

<div class="card">

<div class="card-body" style="text-align:center;">

<h1>💎</h1>

<h2 style="color:gold;margin:15px 0;">

Jewelry

</h2>

<p style="color:#aaa;">

Gold, silver and diamond collections.

</p>

</div>

</div>

<div class="card">

<div class="card-body" style="text-align:center;">

<h1>🌹</h1>

<h2 style="color:gold;margin:15px 0;">

Fragrances

</h2>

<p style="color:#aaa;">

Luxury perfumes for every occasion.

</p>

</div>

</div>

</div>

</div>

<!-- FEATURED PRODUCTS -->

<div
id="featured"
class="section">

<h2 class="section-title">

Featured Products

</h2>

<div class="grid">

<?php

while($row=$featured->fetch_assoc()){

$image=!empty($row['image']) && file_exists("images/".$row['image'])

?

"images/".$row['image']

:

"https://via.placeholder.com/400x300?text=LuxuryVault";

?>

<div class="card">

<img src="<?php echo $image; ?>">

<div class="card-body">

<h3 style="color:gold;">

<?php echo htmlspecialchars($row['name']); ?>

</h3>

<p style="color:#999;margin:10px 0;">

🏪

<?php echo htmlspecialchars($row['shop_name']); ?>

</p>

<div class="price">

TZS

<?php echo number_format($row['price']); ?>

</div>

<div style="
display:flex;
gap:10px;
margin-top:20px;
">

<a
href="product.php?id=<?php echo $row['id']; ?>"
style="flex:1;">

<button
class="hero-btn"
style="
width:100%;
padding:12px;
margin:0;
">

View

</button>

</a>

<a
href="store.php?id=<?php echo $row['seller_id']; ?>"
style="flex:1;">

<button
class="hero-btn"
style="
width:100%;
padding:12px;
margin:0;
background:#222;
color:white;
">

Store

</button>

</a>

</div>

</div>

</div>

<?php

}

?>

</div>

</div>
<!-- FEATURED STORES -->

<div class="section">

<h2 class="section-title">

Featured Stores

</h2>

<div class="grid">

<?php while($store=$stores->fetch_assoc()){ ?>

<?php

$logo=!empty($store['shop_logo']) && file_exists("images/".$store['shop_logo'])

?

"images/".$store['shop_logo']

:

"https://via.placeholder.com/200?text=Store";

?>

<div class="card">

<div class="card-body" style="text-align:center;">

<img
src="<?php echo $logo; ?>"
style="
width:110px;
height:110px;
border-radius:50%;
border:3px solid gold;
object-fit:cover;
margin-bottom:20px;
">

<h3 style="color:gold;">

<?php echo htmlspecialchars($store['shop_name']); ?>

</h3>

<a
href="store.php?id=<?php echo $store['id']; ?>">

<button
class="hero-btn"
style="
margin-top:20px;
width:100%;
padding:12px;
">

Visit Store

</button>

</a>

</div>

</div>

<?php } ?>

</div>

</div>

<!-- LATEST PRODUCTS -->

<div class="section">

<h2 class="section-title">

Latest Arrivals

</h2>

<div class="grid">

<?php while($row=$latest->fetch_assoc()){ ?>

<?php

$image=!empty($row['image']) && file_exists("images/".$row['image'])

?

"images/".$row['image']

:

"https://via.placeholder.com/400";

?>

<div class="card">

<img src="<?php echo $image; ?>">

<div class="card-body">

<h3 style="color:gold;">

<?php echo htmlspecialchars($row['name']); ?>

</h3>

<p style="color:#999;margin:10px 0;">

🏪 <?php echo htmlspecialchars($row['shop_name']); ?>

</p>

<div class="price">

TZS <?php echo number_format($row['price']); ?>

</div>

<a
href="product.php?id=<?php echo $row['id']; ?>">

<button
class="hero-btn"
style="
width:100%;
padding:12px;
margin:0;
">

View Product

</button>

</a>

</div>

</div>

<?php } ?>

</div>

</div>

<!-- WHY US -->

<div
style="
background:#111;
padding:80px 0;
margin-top:70px;
">

<div class="section">

<h2
class="section-title"
style="text-align:center;">

Why LuxuryVault?

</h2>

<div class="grid">

<div class="card">

<div class="card-body" style="text-align:center;">

<h2>✔ Authentic</h2>

<p style="margin-top:15px;color:#aaa;">

Verified luxury products from trusted sellers.

</p>

</div>

</div>

<div class="card">

<div class="card-body" style="text-align:center;">

<h2>🔒 Secure</h2>

<p style="margin-top:15px;color:#aaa;">

Safe shopping and protected transactions.

</p>

</div>

</div>

<div class="card">

<div class="card-body" style="text-align:center;">

<h2>🚚 Fast Delivery</h2>

<p style="margin-top:15px;color:#aaa;">

Reliable nationwide delivery.

</p>

</div>

</div>

<div class="card">

<div class="card-body" style="text-align:center;">

<h2>⭐ Trusted Sellers</h2>

<p style="margin-top:15px;color:#aaa;">

Approved marketplace stores only.

</p>

</div>

</div>

</div>

</div>

</div>

<!-- NEWSLETTER -->

<div class="section" style="text-align:center;">

<h2 class="section-title">

Stay Updated

</h2>

<p
style="
color:#aaa;
margin-bottom:30px;
">

Subscribe to receive updates on new arrivals and exclusive offers.

</p>

<form
style="
display:flex;
justify-content:center;
gap:15px;
flex-wrap:wrap;
">

<input
type="email"
placeholder="Enter your email"
style="
width:380px;
padding:16px;
background:#111;
border:1px solid #333;
border-radius:10px;
color:white;
">

<button
style="
padding:16px 35px;
background:gold;
border:none;
border-radius:10px;
font-weight:bold;
cursor:pointer;
">

Subscribe

</button>

</form>

</div>

<!-- FOOTER -->

<div class="footer">

<div style="margin-bottom:15px;">

<a href="index.php" style="color:gold;margin:0 10px;">Home</a>

<a href="products.php" style="color:gold;margin:0 10px;">Products</a>

<a href="contact.php" style="color:gold;margin:0 10px;">Contact</a>

<?php if(isset($_SESSION['email'])){ ?>

<a href="wishlist.php" style="color:gold;margin:0 10px;">Wishlist</a>

<?php } ?>

</div>

<p>

© 2026 LuxuryVault Marketplace

</p>

<p style="margin-top:10px;color:#666;">

Luxury • Fashion • Watches • Jewelry • Fragrances

</p>

</div>

</body>

</html>