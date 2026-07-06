<?php

session_set_cookie_params([
    'httponly'=>true,
    'samesite'=>'Lax'
]);

session_start();

include("connect.php");

if(!isset($_SESSION['email'])){
    header("Location:login.php");
    exit();
}

$email=$_SESSION['email'];

$stmt=$conn->prepare("

SELECT

wishlist.id wishlist_id,

products.*

FROM wishlist

INNER JOIN products

ON wishlist.product_id=products.id

WHERE wishlist.user_email=?

ORDER BY wishlist.created_at DESC

");

$stmt->bind_param("s",$email);

$stmt->execute();

$wishlist=$stmt->get_result();

$totalItems=$wishlist->num_rows;

?>
<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
content="width=device-width,initial-scale=1.0">

<title>My Wishlist</title>

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

.container{
width:90%;
max-width:1400px;
margin:50px auto;
}

.title{
color:gold;
margin-bottom:10px;
font-size:40px;
}

.subtitle{
color:#999;
margin-bottom:40px;
}

.wishlist{
display:grid;
grid-template-columns:repeat(auto-fit,minmax(300px,1fr));
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
font-size:24px;
font-weight:bold;
margin:15px 0;
}

.stock{
margin-bottom:20px;
}

.actions{
display:flex;
flex-direction:column;
gap:12px;
}

.btn{
padding:14px;
border:none;
border-radius:8px;
font-weight:bold;
cursor:pointer;
width:100%;
}

.view{
background:gold;
color:black;
}

.cart{
background:#2196F3;
color:white;
}

.remove{
background:#d32f2f;
color:white;
}

.empty{
background:#111;
padding:70px;
text-align:center;
border-radius:15px;
border:1px solid #222;
font-size:22px;
color:#888;
}

.footer{
margin-top:60px;
padding:30px;
text-align:center;
background:black;
color:#777;
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

<a href="wishlist.php">Wishlist</a>

<a href="logout.php">Logout</a>

</div>

</div>

<div class="container">

<h1 class="title">

❤️ My Wishlist

</h1>

<p class="subtitle">

<?php echo $totalItems; ?>

saved products

</p>
<div class="wishlist">

<?php

if($wishlist->num_rows > 0){

while($row = $wishlist->fetch_assoc()){

$image = !empty($row['image']) &&
file_exists("images/".$row['image'])

?

"images/".$row['image']

:

"https://via.placeholder.com/400x300?text=LuxuryVault";

?>

<div class="card">

<img
src="<?php echo htmlspecialchars($image); ?>">

<div class="card-body">

<h3>

<?php echo htmlspecialchars($row['name']); ?>

</h3>

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

echo "<span style='color:#f44336;'>Out Of Stock</span>";

}

?>

</div>

<div class="actions">

<a href="product.php?id=<?php echo (int)$row['id']; ?>">

<button class="btn view">

View Product

</button>

</a>

<?php if($row['quantity']>0){ ?>

<a href="add_to_cart.php?id=<?php echo (int)$row['id']; ?>">

<button class="btn cart">

🛒 Move To Cart

</button>

</a>

<?php } ?>

<a href="remove_wishlist.php?id=<?php echo (int)$row['id']; ?>">

<button class="btn remove">

❌ Remove

</button>

</a>

</div>

</div>

</div>

<?php

}

}else{

?>

<div class="empty">

<h2 style="margin-bottom:20px;">

❤️ Your wishlist is empty

</h2>

<p style="margin-bottom:30px;">

Save your favorite products here and buy them later.

</p>

<a href="products.php">

<button
class="btn view"
style="max-width:250px;">

Browse Products

</button>

</a>

</div>

<?php

}

?>

</div>

</div>

<div class="footer">

© 2026 LuxuryVault Marketplace

</div>

</body>

</html>