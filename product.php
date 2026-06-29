<?php
session_set_cookie_params([
    'httponly' => true,
    'samesite' => 'Lax'
]);

session_start();
include("connect.php");

// Check if product id exists
if(!isset($_GET['id'])){
    header("Location: products.php");
    exit();
}

$id = $_GET['id'];

// Fetch selected product
$query = mysqli_query($conn, "SELECT * FROM products WHERE id='$id'");

if(mysqli_num_rows($query) == 0){
    header("Location: products.php");
    exit();
}

$row = mysqli_fetch_assoc($query);

$image = !empty($row['image'])
    ? 'images/'.$row['image']
    : 'https://via.placeholder.com/500x500?text=LuxuryVault';

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php echo $row['name']; ?></title>

<style>

body{
    background:#0b0b0b;
    color:white;
    font-family:Poppins,sans-serif;
    margin:0;
}

.navbar{
    display:flex;
    justify-content:space-between;
    align-items:center;
    padding:20px 50px;
    background:black;
}

.logo{
    color:gold;
    font-size:25px;
    font-weight:bold;
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
    max-width:1100px;
    margin:60px auto;
    display:flex;
    gap:50px;
    flex-wrap:wrap;
}

.image-section{
    flex:1;
}

.image-section img{
    width:100%;
    border-radius:15px;
}

.info-section{
    flex:1;
}

.info-section h1{
    color:gold;
}

.category{
    color:#999;
    margin-top:10px;
}

.price{
    font-size:32px;
    margin:20px 0;
    font-weight:bold;
}

.description{
    color:#ccc;
    line-height:1.8;
}

.btn{
    margin-top:30px;
    padding:15px 35px;
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

.footer{
    text-align:center;
    padding:30px;
    color:#777;
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

<!-- PRODUCT DETAILS -->

<div class="container">

<div class="image-section">

<img src="<?php echo $image; ?>">

</div>

<div class="info-section">

<h1><?php echo $row['name']; ?></h1>

<div class="category">
Category: <?php echo $row['category']; ?>
</div>

<div class="price">
TZS <?php echo number_format($row['price']); ?>
</div>

<div class="description">
<?php echo $row['description']; ?>
</div>

<?php if(isset($_SESSION['email'])){ ?>

<a href="add_to_cart.php?id=<?php echo $row['id']; ?>">
    <button class="btn">
        Add To Cart
    </button>
</a>

<?php } else { ?>

<a href="login.php">
    <button class="btn">
        Login To Buy
    </button>
</a>

<?php } ?>

</div>

</div>

<div class="footer">
    © 2026 LuxuryVault |
    <a href="contact.php">Contact Us</a>
</div>

</body>
</html>