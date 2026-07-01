<?php
session_set_cookie_params([
    'httponly' => true,
    'samesite' => 'Lax'
]);

session_start();
include("connect.php");

// Validate Product ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: products.php");
    exit();
}

$id = (int)$_GET['id'];

// Fetch Product Securely
$stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows == 0) {
    header("Location: products.php");
    exit();
}

$row = $result->fetch_assoc();

$image = (!empty($row['image']) && file_exists("images/" . $row['image']))
    ? "images/" . htmlspecialchars($row['image'])
    : "https://via.placeholder.com/500x500?text=LuxuryVault";
?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title><?php echo htmlspecialchars($row['name']); ?></title>

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
font-size:28px;
font-weight:bold;
color:gold;
}

.nav-links a{
color:white;
text-decoration:none;
margin-left:20px;
transition:.3s;
}

.nav-links a:hover{
color:gold;
}

.container{
width:90%;
max-width:1200px;
margin:60px auto;
display:flex;
gap:60px;
flex-wrap:wrap;
align-items:flex-start;
}

.image-section{
flex:1;
min-width:350px;
}

.image-section img{
width:100%;
border-radius:15px;
border:1px solid #222;
}

.info-section{
flex:1;
min-width:350px;
}

.info-section h1{
font-size:42px;
color:gold;
margin-bottom:10px;
}

.category{
color:#999;
margin-bottom:25px;
}

.price{
font-size:35px;
font-weight:bold;
margin-bottom:25px;
}

.description{
line-height:1.8;
color:#ccc;
margin-bottom:35px;
}

.btn{
padding:15px 40px;
background:gold;
color:black;
border:none;
font-weight:bold;
font-size:16px;
cursor:pointer;
border-radius:6px;
transition:.3s;
}

.btn:hover{
background:#d4af37;
}

.footer{
margin-top:80px;
padding:30px;
text-align:center;
background:black;
color:#888;
}

.footer a{
color:gold;
text-decoration:none;
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

<a href="register.php">Register</a>

<?php } ?>

</div>

</div>

<div class="container">

<div class="image-section">

<img src="<?php echo $image; ?>"
alt="<?php echo htmlspecialchars($row['name']); ?>">

</div>

<div class="info-section">

<h1>
<?php echo htmlspecialchars($row['name']); ?>
</h1>

<div class="category">
Category :
<?php echo htmlspecialchars($row['category']); ?>
</div>

<div class="price">
TZS <?php echo number_format($row['price']); ?>
</div>

<div class="description">
<?php echo nl2br(htmlspecialchars($row['description'])); ?>
</div>

<?php if(isset($_SESSION['email'])){ ?>

<a href="add_to_cart.php?id=<?php echo (int)$row['id']; ?>">

<button class="btn">
Add To Cart
</button>

</a>

<?php }else{ ?>

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