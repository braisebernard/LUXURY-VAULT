<?php
session_set_cookie_params([
'httponly'=>true,
'samesite'=>'Lax'
]);

session_start();
include("connect.php");

if(!isset($_GET['id']) || !is_numeric($_GET['id'])){
header("Location:products.php");
exit();
}

$sellerId=(int)$_GET['id'];

$stmt=$conn->prepare("
SELECT
id,
shop_name,
shop_logo,
shop_banner,
shop_description,
phone,
address,
facebook,
instagram,
website
FROM users
WHERE id=?
AND role='seller'
");

$stmt->bind_param("i",$sellerId);
$stmt->execute();

$store=$stmt->get_result()->fetch_assoc();

if(!$store){
die("Store not found.");
}

$stmt=$conn->prepare("
SELECT *
FROM products
WHERE seller_id=?
AND status='approved'
ORDER BY id DESC
");

$stmt->bind_param("i",$sellerId);
$stmt->execute();

$products=$stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0">

<title><?php echo htmlspecialchars($store['shop_name']); ?></title>

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
display:flex;
justify-content:space-between;
align-items:center;
padding:20px 50px;
background:black;
border-bottom:1px solid #222;
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

.banner{
width:100%;
height:320px;
object-fit:cover;
display:block;
}

.store-box{
width:90%;
margin:auto;
margin-top:-70px;
}

.header{
display:flex;
align-items:flex-end;
gap:30px;
flex-wrap:wrap;
}

.store-logo{
width:170px;
height:170px;
border-radius:50%;
border:5px solid gold;
background:#111;
object-fit:cover;
}

.info{
flex:1;
}

.info h1{
font-size:42px;
color:gold;
margin-bottom:10px;
}

.info p{
margin:8px 0;
color:#ccc;
line-height:1.7;
}

.links{
margin-top:15px;
}

.links a{
display:inline-block;
margin-right:15px;
color:gold;
text-decoration:none;
font-weight:bold;
}

.section-title{
width:90%;
margin:45px auto 25px;
font-size:34px;
color:gold;
}

.products{
width:90%;
margin:auto;
display:grid;
grid-template-columns:repeat(auto-fit,minmax(280px,1fr));
gap:25px;
padding-bottom:50px;
}

.card{
background:#111;
border:1px solid #222;
border-radius:15px;
overflow:hidden;
transition:.3s;
}

.card:hover{
transform:translateY(-5px);
box-shadow:0 10px 20px rgba(255,215,0,.15);
}

.card img{
width:100%;
height:260px;
object-fit:cover;
}

.card-body{
padding:20px;
}

.card-body h3{
color:gold;
margin-bottom:10px;
}

.price{
font-size:22px;
font-weight:bold;
margin:15px 0;
}

.stock{
margin-bottom:18px;
font-weight:bold;
}

.btn{
width:100%;
padding:14px;
background:gold;
color:black;
border:none;
border-radius:8px;
font-weight:bold;
cursor:pointer;
}

.btn:hover{
background:#d4af37;
}

.footer{
background:black;
text-align:center;
padding:30px;
color:#777;
margin-top:40px;
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

<?php if(isset($_SESSION['email'])){ ?>

<a href="cart.php">Cart</a>
<a href="wishlist.php">Wishlist</a>
<a href="logout.php">Logout</a>

<?php }else{ ?>

<a href="login.php">Login</a>

<?php } ?>

</div>

</div>

<img class="banner"
src="<?php echo !empty($store['shop_banner']) ? "images/".$store['shop_banner'] : "https://via.placeholder.com/1600x350?text=LuxuryVault+Store"; ?>">

<div class="store-box">

<div class="header">

<img class="store-logo"
src="<?php echo !empty($store['shop_logo']) ? "images/".$store['shop_logo'] : "https://via.placeholder.com/200?text=Store"; ?>">

<div class="info">

<h1><?php echo htmlspecialchars($store['shop_name']); ?></h1>

<p><?php echo nl2br(htmlspecialchars($store['shop_description'])); ?></p>

<p>📞 <?php echo htmlspecialchars($store['phone']); ?></p>

<p>📍 <?php echo htmlspecialchars($store['address']); ?></p>

<div class="links">

<?php if($store['facebook']!=""){ ?>
<a target="_blank" href="<?php echo htmlspecialchars($store['facebook']); ?>">Facebook</a>
<?php } ?>

<?php if($store['instagram']!=""){ ?>
<a target="_blank" href="<?php echo htmlspecialchars($store['instagram']); ?>">Instagram</a>
<?php } ?>

<?php if($store['website']!=""){ ?>
<a target="_blank" href="<?php echo htmlspecialchars($store['website']); ?>">Website</a>
<?php } ?>

</div>

</div>

</div>

<h2 class="section-title">Products</h2>

<div class="products">
 <?php

if($products->num_rows>0){

while($row=$products->fetch_assoc()){

$image=!empty($row['image']) && file_exists("images/".$row['image'])
? "images/".$row['image']
: "https://via.placeholder.com/400x300?text=LuxuryVault";

?>

<div class="card">

<img src="<?php echo htmlspecialchars($image); ?>">

<div class="card-body">

<h3><?php echo htmlspecialchars($row['name']); ?></h3>

<p style="color:#999;">
<?php echo htmlspecialchars($row['category']); ?>
</p>

<div class="price">
TZS <?php echo number_format($row['price']); ?>
</div>

<div class="stock">

<?php

if($row['quantity']>0){

echo "<span style='color:#4CAF50;'>✔ ".$row['quantity']." In Stock</span>";

}else{

echo "<span style='color:#ff5252;'>Out Of Stock</span>";

}

?>

</div>

<p style="color:#bbb;line-height:1.6;margin-bottom:20px;">

<?php

echo htmlspecialchars(substr($row['description'],0,80));

?>

...

</p>

<a href="product.php?id=<?php echo (int)$row['id']; ?>">

<button class="btn">

View Product

</button>

</a>

</div>

</div>

<?php

}

}else{

?>

<div style="
grid-column:1/-1;
background:#111;
padding:80px;
border-radius:15px;
border:1px solid #222;
text-align:center;
">

<h2 style="color:#999;margin-bottom:20px;">

This seller has not published any products yet.

</h2>

<a href="products.php">

<button class="btn" style="max-width:250px;">

Browse Marketplace

</button>

</a>

</div>

<?php

}

?>

</div>

<div class="footer">

© 2026 LuxuryVault Marketplace

</div>

</body>

</html>   