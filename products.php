<?php
session_set_cookie_params([
    'httponly' => true,
    'samesite' => 'Lax'
]);

session_start();
include("config/connect.php");

/* ============================
   SEARCH & FILTER
============================ */

$search = "";
$category = "";

if(isset($_GET['search'])){
    $search = trim($_GET['search']);
}

if(isset($_GET['category'])){
    $category = trim($_GET['category']);
}

/* ============================
   BUILD QUERY
============================ */

$sql = "

SELECT

products.*,

users.shop_name

FROM products

LEFT JOIN users

ON products.seller_id = users.id

WHERE products.status='approved'

";

$params = [];
$types = "";

if($search != ""){

    $sql .= " AND products.name LIKE ? ";

    $params[] = "%".$search."%";
    $types .= "s";

}

if($category != ""){

    $sql .= " AND products.category=? ";

    $params[] = $category;
    $types .= "s";

}

$sql .= " ORDER BY products.id DESC";
$stmt = $conn->prepare($sql);
if(!empty($params)){
    $stmt->bind_param($types,...$params);
}

$stmt->execute();
$query = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>LuxuryVault Products</title>

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:'Poppins',sans-serif;
}

body{
    background:#0b0b0b;
    color:white;
}

/* NAVBAR */
.navbar{
    display:flex;
    justify-content:space-between;
    align-items:center;
    padding:20px 50px;
    background:black;
    border-bottom:1px solid #222;
}

.logo{
    color:gold;
    font-size:24px;
    font-weight:bold;
}

.nav-links a{
    color:#ccc;
    text-decoration:none;
    margin-left:25px;
}

.nav-links a:hover{
    color:gold;
}

/* TITLE */
.page-title{
    text-align:center;
    padding:50px 20px;
}

.page-title h1{
    color:gold;
    font-size:45px;
}

.page-title p{
    color:#aaa;
    margin-top:10px;
}

/* SEARCH */
.search-section{
    width:90%;
    margin:auto;
    margin-bottom:30px;
}

.search-form{
    display:flex;
    gap:15px;
    flex-wrap:wrap;
}

.search-form input,
.search-form select{
    flex:1;
    padding:15px;
    background:#111;
    border:1px solid #333;
    color:white;
    border-radius:8px;
}

.search-form button{
    background:gold;
    color:black;
    border:none;
    padding:15px 30px;
    border-radius:8px;
    font-weight:bold;
    cursor:pointer;
}

/* PRODUCTS */
.product-grid{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(280px,1fr));
    gap:25px;
    padding:40px;
}

.product-card{
    background:#111;
    border:1px solid #222;
    border-radius:12px;
    overflow:hidden;
    transition:.3s;
}

.product-card:hover{
    transform:translateY(-5px);
    box-shadow:0 10px 20px rgba(255,215,0,.15);
}

.product-card img{
    width:100%;
    height:260px;
    object-fit:cover;
}

.product-info{
    padding:20px;
}

.product-info h3{
    color:gold;
    margin-bottom:10px;
}

.category{
    color:#999;
    font-size:14px;
    margin-bottom:10px;
}

.price{
    font-size:22px;
    font-weight:bold;
    margin-bottom:15px;
}

.description{
    color:#bbb;
    margin-bottom:20px;
    line-height:1.6;
}

/* BUTTON */
.btn{
    width:100%;
    padding:12px;
    border:none;
    background:gold;
    color:black;
    font-weight:bold;
    border-radius:5px;
    cursor:pointer;
}

.btn:hover{
    background:#d4af37;
}

/* FOOTER */
.footer{
    text-align:center;
    padding:25px;
    background:black;
    color:#777;
    margin-top:50px;
}

</style>
</head>
<body>

<!-- NAVBAR -->
<div class="navbar">

    <div class="logo">LuxuryVault</div>

    <div class="nav-links">

        <a href="index.php">Home</a>
        <a href="products.php">Products</a>

        <?php if(isset($_SESSION['email'])){ ?>

            <a href="cart.php">Cart</a>
            <a href="logout.php">Logout</a>

        <?php } else { ?>

            <a href="login.php">Login</a>
            <a href="register.php">Register</a>

        <?php } ?>

    </div>

</div>

<!-- TITLE -->
<div class="page-title">

    <h1>Luxury Collection</h1>
    <p>Explore premium and authentic luxury products</p>

</div>

<!-- SEARCH -->
<div class="search-section">

<form method="GET" class="search-form">

<input
type="text"
name="search"
placeholder="Search products..."
value="<?php echo $search; ?>">

<select name="category">

<option value="">All Categories</option>
<option value="Watches">Watches</option>
<option value="Perfumes">Perfumes</option>
<option value="Bags">Bags</option>
<option value="Jewelry">Jewelry</option>

</select>

<button type="submit">
Search
</button>

</form>

</div>

<!-- PRODUCTS -->
<div class="product-grid">

<?php

if(mysqli_num_rows($query) > 0){

while($row = mysqli_fetch_assoc($query)){

$image = !empty($row['image'])
? "images/".$row['image']
: "https://via.placeholder.com/400x300?text=LuxuryVault";

?>

<div class="product-card">

<img src="<?php echo htmlspecialchars($image); ?>">

<div class="product-info">

<h3>

<?php echo htmlspecialchars($row['name']); ?>

</h3>

<div class="category">

<?php echo htmlspecialchars($row['category']); ?>

</div>

<p style="margin-bottom:10px;color:#bbb;">

<strong>Seller:</strong>

<?php

if(empty($row['seller_id'])){

    echo "LuxuryVault";

}else{

    echo htmlspecialchars($row['shop_name']);

}

?>

</p>

<div class="price">

TZS <?php echo number_format($row['price']); ?>

</div>

<p style="margin-bottom:10px;">

<strong>Stock:</strong>

<?php

if($row['quantity']>0){

    echo "<span style='color:#4CAF50;'>".$row['quantity']." Available</span>";

}else{

    echo "<span style='color:red;'>Out Of Stock</span>";

}

?>

</p>

<div class="description">

<?php

echo htmlspecialchars(

substr($row['description'],0,45)

)." ...";

?>

</div>

<?php

if($row['quantity']>0){

    if(isset($_SESSION['email'])){

?>

<a href="product.php?id=<?php echo (int)$row['id']; ?>">

<button class="btn">

View Product

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

class="btn"

disabled

style="background:#555;cursor:not-allowed;">

Out Of Stock

</button>

<?php } ?>

</div>

</div>

<?php
}
}else{
    echo "<h2 style='text-align:center;color:gray;width:100%;'>
    No products found
    </h2>";
}
?>

</div>
<div class="footer">
    © 2026 LuxuryVault |
    <a href="contact.php">Contact Us</a>
</div>

</body>
</html>