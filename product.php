<?php

session_set_cookie_params([
    'httponly'=>true,
    'samesite'=>'Lax'
]);

session_start();

include("config/connect.php");

/* ===========================
   PRODUCT ID
=========================== */

if(
    !isset($_GET['id']) ||
    !is_numeric($_GET['id'])
){

    header("Location:products.php");
    exit();

}

$id=(int)$_GET['id'];

/* ===========================
   PRODUCT
=========================== */

$stmt=$conn->prepare("

SELECT

products.*,

users.shop_name,

users.shop_logo,

users.phone,

users.address

FROM products

LEFT JOIN users

ON products.seller_id=users.id

WHERE products.id=?

AND products.status='approved'

");

$stmt->bind_param("i",$id);

$stmt->execute();

$result=$stmt->get_result();

if($result->num_rows==0){

header("Location:products.php");
exit();

}

$product=$result->fetch_assoc();

/* ===========================
   IMAGE
=========================== */

$image=

!empty($product['image']) &&
file_exists("images/".$product['image'])

?

"images/".$product['image']

:

"https://via.placeholder.com/600x600?text=LuxuryVault";

/* ===========================
   REVIEWS
=========================== */

$stmt=$conn->prepare("

SELECT *

FROM reviews

WHERE product_id=?

ORDER BY id DESC

");

$stmt->bind_param("i",$id);

$stmt->execute();

$reviews=$stmt->get_result();

/* ===========================
   RATING
=========================== */

$stmt=$conn->prepare("

SELECT

AVG(rating) average,

COUNT(*) total

FROM reviews

WHERE product_id=?

");

$stmt->bind_param("i",$id);

$stmt->execute();

$ratingData=

$stmt->get_result()->fetch_assoc();

$average=

round($ratingData['average'],1);

$totalReviews=

$ratingData['total'];

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
content="width=device-width,initial-scale=1.0">

<title>

<?php echo htmlspecialchars($product['name']); ?>

</title>

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
display:grid;
grid-template-columns:1fr 1fr;
gap:50px;
}

.product-image img{
width:100%;
border-radius:15px;
border:1px solid #222;
}

.product-info h1{
font-size:45px;
color:gold;
margin-bottom:15px;
}

.category{
color:#999;
margin-bottom:15px;
}

.price{
font-size:38px;
font-weight:bold;
margin-bottom:20px;
}

.rating{
margin-bottom:20px;
font-size:18px;
color:gold;
}

.stock{
margin-bottom:25px;
font-weight:bold;
}

.description{
line-height:1.9;
color:#ccc;
margin-bottom:30px;
}

.seller{

background:#111;
padding:20px;
border-radius:12px;
border:1px solid #222;
margin-bottom:30px;

}

.seller-top{

display:flex;
align-items:center;
gap:20px;

}

.seller-logo{

width:80px;
height:80px;
border-radius:50%;
object-fit:cover;
border:3px solid gold;

}

.seller-name{

font-size:24px;
color:gold;

}

.visit{

display:inline-block;

margin-top:20px;

padding:12px 25px;

background:gold;

color:black;

text-decoration:none;

border-radius:8px;

font-weight:bold;

}

.btn{

padding:16px 40px;

background:gold;

border:none;

border-radius:8px;

font-weight:bold;

cursor:pointer;

font-size:18px;

}

.btn:hover{

background:#d4af37;

}

.disabled{

background:#555;

cursor:not-allowed;

}

.section-title{

width:90%;
margin:40px auto 20px;
font-size:35px;
color:gold;

}

.reviews{

width:90%;
margin:auto;

}

.review{

background:#111;

padding:25px;

margin-bottom:20px;

border-radius:15px;

border:1px solid #222;

}

.review h3{

color:gold;

margin-bottom:10px;

}

.review-date{

color:#888;

margin-top:15px;

font-size:14px;

}

textarea{

width:100%;

padding:15px;

background:#1a1a1a;

color:white;

border:1px solid #333;

border-radius:10px;

margin:15px 0;

height:140px;

resize:none;

}

select{

width:100%;

padding:15px;

background:#1a1a1a;

color:white;

border:1px solid #333;

border-radius:10px;

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

<?php if(isset($_SESSION['email'])){ ?>

<a href="cart.php">Cart</a>

<a href="my_orders.php">My Orders</a>

<a href="logout.php">Logout</a>

<?php }else{ ?>

<a href="login.php">Login</a>

<?php } ?>

</div>

</div>

<div class="container">

<div class="product-image">

<img src="<?php echo htmlspecialchars($image); ?>">

</div>

<div class="product-info">

<h1>

<?php echo htmlspecialchars($product['name']); ?>

</h1>

<div class="category">

<?php echo htmlspecialchars($product['category']); ?>

</div>

<div class="rating">

⭐ <?php echo $average ?: "0"; ?>/5

(<?php echo $totalReviews; ?> Reviews)

</div>

<div class="price">

TZS <?php echo number_format($product['price']); ?>

</div>

<div class="stock">

<?php

if($product['quantity']>0){

echo "<span style='color:#4CAF50;'>✔ ".$product['quantity']." In Stock</span>";

}else{

echo "<span style='color:red;'>Out Of Stock</span>";

}

?>

</div>

<div class="description">

<?php echo nl2br(htmlspecialchars($product['description'])); ?>

</div>

<div class="seller">

<div class="seller-top">

<img

class="seller-logo"

src="<?php

if(!empty($product['shop_logo'])){

echo "images/".htmlspecialchars($product['shop_logo']);

}else{

echo "https://via.placeholder.com/100?text=Shop";

}

?>">

<div>

<div class="seller-name">

<?php

echo !empty($product['shop_name'])

? htmlspecialchars($product['shop_name'])

: "LuxuryVault";

?>

</div>

<div>

📞 <?php echo htmlspecialchars($product['phone']); ?>

</div>

<div>

📍 <?php echo htmlspecialchars($product['address']); ?>

</div>

</div>

</div>

<?php if(!empty($product['seller_id'])){ ?>

<a

class="visit"

href="store.php?id=<?php echo (int)$product['seller_id']; ?>">

🏪 Visit Store

</a>

<?php } ?>

</div>

<?php

if($product['quantity']>0){

if(isset($_SESSION['email'])){

?>

<a href="add_to_cart.php?id=<?php echo (int)$product['id']; ?>">

<button class="btn">

Add To Cart

</button>

</a>

<a href="add_to_wishlist.php?id=<?php echo (int)$product['id']; ?>">

<button
class="btn"
style="margin-left:10px;background:#333;color:white;">

❤️ Wishlist

</button>

</a>

<?php

}else{

?>

<a href="login.php">

<button class="btn">

Login To Buy

</button>

</a>

<?php

}

}else{

?>

<button

class="btn disabled"

disabled>

Out Of Stock

</button>

<?php } ?>

</div>

</div>

<h2 class="section-title">

Customer Reviews

</h2>

<div class="reviews">

<?php if(isset($_SESSION['email'])){ ?>

<div class="review">

<h3>Write a Review</h3>

<form action="submit_review.php" method="POST">

<input
type="hidden"
name="product_id"
value="<?php echo (int)$product['id']; ?>">

<label>Rating</label>

<select name="rating" required>

<option value="">Select Rating</option>

<option value="5">⭐⭐⭐⭐⭐ Excellent</option>

<option value="4">⭐⭐⭐⭐ Very Good</option>

<option value="3">⭐⭐⭐ Good</option>

<option value="2">⭐⭐ Fair</option>

<option value="1">⭐ Poor</option>

</select>

<label style="display:block;margin-top:20px;">
Comment
</label>

<textarea
name="comment"
placeholder="Share your experience..."
required></textarea>

<button
type="submit"
class="btn">

Submit Review

</button>

</form>

</div>

<?php }else{ ?>

<div class="review">

<h3>Want to leave a review?</h3>

<p style="margin-top:15px;">

<a href="login.php" style="color:gold;">

Login

</a>

to review this product.

</p>

</div>

<?php } ?>

<?php

if($reviews->num_rows>0){

while($review=$reviews->fetch_assoc()){

?>

<div class="review">

<h3>

<?php echo htmlspecialchars($review['user_email']); ?>

<span style="color:#4CAF50;font-size:14px;">

✔ Verified Purchase

</span>

</h3>

<p style="color:gold;font-size:18px;margin:10px 0;">

<?php

for($i=1;$i<=5;$i++){

    if($i<=$review['rating']){

        echo "★";

    }else{

        echo "☆";

    }

}

?>

</p>

<p>

<?php

echo nl2br(

htmlspecialchars($review['comment'])

);

?>

</p>

<?php if(isset($review['created_at'])){ ?>

<div class="review-date">

<?php

echo date(

"d M Y",

strtotime($review['created_at'])

);

?>

</div>

<?php } ?>

</div>

<?php

}

}else{

?>

<div class="review">

<h3>No Reviews Yet</h3>

<p>

Be the first customer to review this product.

</p>

</div>

<?php

}

?>

</div>

<div class="footer">

© 2026 LuxuryVault Marketplace |

<a
href="contact.php"
style="color:gold;">

Contact Us

</a>

</div>

</body>

</html>

</body>

</html>