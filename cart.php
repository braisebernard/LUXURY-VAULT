<?php
session_start();
include("connect.php");

if(!isset($_SESSION['email'])){
    header("Location: login.php");
    exit();
}

$email = $_SESSION['email'];

$query = mysqli_query($conn,
"SELECT cart.*, products.*
FROM cart
JOIN products ON cart.product_id = products.id
WHERE cart.user_email='$email'");

$total = 0;
?>

<!DOCTYPE html>
<html>
<head>
<title>LuxuryVault Cart</title>

<style>

body{
    background:#0b0b0b;
    color:white;
    font-family:Poppins,sans-serif;
}

.container{
    width:90%;
    margin:auto;
}

.item{
    background:#111;
    padding:20px;
    margin:20px 0;
    border-radius:10px;
}

.btn{
    background:red;
    color:white;
    border:none;
    padding:10px 20px;
    cursor:pointer;
}

</style>

</head>
<body>

<div class="container">

<h1>Your Cart</h1>

<?php while($row = mysqli_fetch_assoc($query)){ ?>

<?php
$subtotal = $row['price'] * $row['quantity'];
$total += $subtotal;
?>

<div class="item">

<h2><?php echo $row['name']; ?></h2>

<p>
Price:
TZS <?php echo number_format($row['price']); ?>
</p>

<p>
Subtotal:
TZS <?php echo number_format($subtotal); ?>
</p>

<p>

<a href="decrease_quantity.php?id=<?php echo $row['product_id']; ?>">
<button>-</button>
</a>

Quantity:
<?php echo $row['quantity']; ?>

<a href="increase_quantity.php?id=<?php echo $row['product_id']; ?>">
<button>+</button>
</a>

</p>

<a href="remove_from_cart.php?id=<?php echo $row['product_id']; ?>">
<button class="btn">
Remove
</button>
</a>

</div>

<?php } ?>

<h2>
Total: TZS <?php echo number_format($total); ?>
</h2>
<br>

<a href="checkout.php">
<button class="btn">
Proceed To Checkout
</button>
</a>

</div>

</body>
</html>