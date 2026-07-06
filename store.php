<div class="main">

<h1 class="title">

Store Profile

</h1>

<?php if(isset($_GET['saved'])){ ?>

<div class="success">

✅ Your store profile has been updated successfully.

</div>

<?php } ?>

<div class="profile-box">

<div class="preview">

<div>

<p style="color:gold;margin-bottom:10px;font-weight:bold;">

Store Logo

</p>

<img

class="logo-preview"

src="<?php

if(!empty($user['shop_logo'])){

echo "images/".htmlspecialchars($user['shop_logo']);

}else{

echo "https://via.placeholder.com/150?text=Logo";

}

?>">

</div>

<div style="flex:1;">

<p style="color:gold;margin-bottom:10px;font-weight:bold;">

Store Banner

</p>

<img

class="banner"

src="<?php

if(!empty($user['shop_banner'])){

echo "images/".htmlspecialchars($user['shop_banner']);

}else{

echo "https://via.placeholder.com/900x250?text=Store+Banner";

}

?>">

</div>

</div>

<form method="POST" enctype="multipart/form-data">

<label>Store Name</label>

<input

type="text"

name="shop_name"

value="<?php echo htmlspecialchars($user['shop_name']); ?>"

required>

<div class="row">

<div>

<label>Upload Logo</label>

<input

type="file"

name="shop_logo"

accept=".jpg,.jpeg,.png,.webp">

</div>

<div>

<label>Upload Banner</label>

<input

type="file"

name="shop_banner"

accept=".jpg,.jpeg,.png,.webp">

</div>

</div>

<label>Store Description</label>

<textarea

name="shop_description"><?php echo htmlspecialchars($user['shop_description']); ?></textarea>

<div class="row">

<div>

<label>Phone Number</label>

<input

type="text"

name="phone"

value="<?php echo htmlspecialchars($user['phone']); ?>">

</div>

<div>

<label>Business Address</label>

<input

type="text"

name="address"

value="<?php echo htmlspecialchars($user['address']); ?>">

</div>

</div>

<div class="row">

<div>

<label>Facebook</label>

<input

type="text"

name="facebook"

placeholder="https://facebook.com/yourpage"

value="<?php echo htmlspecialchars($user['facebook']); ?>">

</div>

<div>

<label>Instagram</label>

<input

type="text"

name="instagram"

placeholder="https://instagram.com/yourpage"

value="<?php echo htmlspecialchars($user['instagram']); ?>">

</div>

</div>

<label>Website</label>

<input

type="text"

name="website"

placeholder="https://www.yourstore.com"

value="<?php echo htmlspecialchars($user['website']); ?>">

<button

type="submit"

name="saveProfile">

Save Store Profile

</button>

</form>

</div>

</div>

</body>

</html>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
content="width=device-width, initial-scale=1.0">

<title>

<?php echo htmlspecialchars($store['shop_name']); ?>

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

/* Banner */

.banner{

width:100%;
height:320px;
object-fit:cover;
display:block;

}

/* Header */

.store-header{

width:90%;
margin:auto;
margin-top:-70px;

display:flex;
align-items:flex-end;
gap:30px;

}

.logo{

width:170px;
height:170px;

border-radius:50%;
border:6px solid gold;

object-fit:cover;

background:#111;

}

/* Store Info */

.info{

flex:1;

}

.info h1{

color:gold;
font-size:40px;
margin-bottom:15px;

}

.info p{

line-height:1.8;
color:#ccc;

}

.social{

margin-top:20px;

}

.social a{

display:inline-block;

margin-right:15px;

color:gold;

text-decoration:none;

font-weight:bold;

}

/* Products */

.section-title{

width:90%;
margin:50px auto 30px;

font-size:34px;

color:gold;

}

.products{

width:90%;
margin:auto;

display:grid;

grid-template-columns:

repeat(auto-fit,minmax(280px,1fr));

gap:25px;

padding-bottom:60px;

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

box-shadow:0 15px 30px rgba(255,215,0,.15);

}

.card img{

width:100%;

height:250px;

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

margin-bottom:15px;

color:#bbb;

}

.btn{

width:100%;

padding:14px;

background:gold;

border:none;

border-radius:8px;

font-weight:bold;

cursor:pointer;

}

.btn:hover{

background:#d4af37;

}

.footer{

text-align:center;

padding:30px;

background:black;

color:#777;

}

</style>

</head>

<body>

<img

class="banner"

src="<?php

if(!empty($store['shop_banner'])){

echo "images/".htmlspecialchars($store['shop_banner']);

}else{

echo "https://via.placeholder.com/1600x350?text=LuxuryVault+Store";

}

?>">

<div class="store-header">

<img

class="logo"

src="<?php

if(!empty($store['shop_logo'])){

echo "images/".htmlspecialchars($store['shop_logo']);

}else{

echo "https://via.placeholder.com/200?text=Logo";

}

?>">

<div class="info">

<h1>

<?php

echo htmlspecialchars($store['shop_name']);

?>

</h1>

<p>

<?php

echo nl2br(

htmlspecialchars($store['shop_description'])

);

?>

</p>

<p>

📞

<?php echo htmlspecialchars($store['phone']); ?>

</p>

<p>

📍

<?php echo htmlspecialchars($store['address']); ?>

</p>

<div class="social">

<?php if(!empty($store['facebook'])){ ?>

<a target="_blank"

href="<?php echo htmlspecialchars($store['facebook']); ?>">

Facebook

</a>

<?php } ?>

<?php if(!empty($store['instagram'])){ ?>

<a target="_blank"

href="<?php echo htmlspecialchars($store['instagram']); ?>">

Instagram

</a>

<?php } ?>

<?php if(!empty($store['website'])){ ?>

<a target="_blank"

href="<?php echo htmlspecialchars($store['website']); ?>">

Website

</a>

<?php } ?>

</div>

</div>

</div>

<h2 class="section-title">

Products

</h2>

<div class="products">

<?php

if($products->num_rows > 0){

while($row = $products->fetch_assoc()){

?>

<div class="card">

<img
src="images/<?php echo htmlspecialchars($row['image']); ?>">

<div class="card-body">

<h3>

<?php echo htmlspecialchars($row['name']); ?>

</h3>

<p>

<?php echo htmlspecialchars($row['category']); ?>

</p>

<div class="price">

TZS <?php echo number_format($row['price']); ?>

</div>

<div class="stock">

<?php

if($row['quantity'] > 0){

echo "<span style='color:#4CAF50;'>✔ ".$row['quantity']." In Stock</span>";

}else{

echo "<span style='color:red;'>Out Of Stock</span>";

}

?>

</div>

<p style="color:#bbb;margin-bottom:20px;">

<?php

echo htmlspecialchars(

substr($row['description'],0,80)

);

?>...

</p>

<?php if($row['quantity']>0){ ?>

<a href="product.php?id=<?php echo (int)$row['id']; ?>">

<button class="btn">

View Product

</button>

</a>

<?php }else{ ?>

<button

class="btn"

disabled

style="background:#666;cursor:not-allowed;">

Out Of Stock

</button>

<?php } ?>

</div>

</div>

<?php

}

}else{

?>

<div style="grid-column:1/-1;text-align:center;padding:80px;">

<h2 style="color:#999;">

This store has no approved products yet.

</h2>

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