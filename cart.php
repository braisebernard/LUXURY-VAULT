<?php

session_set_cookie_params([
    'httponly' => true,
    'samesite' => 'Lax'
]);

session_start();
include("config/connect.php");

if(!isset($_SESSION['email'])){
    header("Location: login.php");
    exit();
}

$email = $_SESSION['email'];

// Secure Query
$stmt = $conn->prepare("
SELECT
cart.product_id,
cart.quantity,
products.name,
products.price,
products.image
FROM cart
INNER JOIN products
ON cart.product_id = products.id
WHERE cart.user_email = ?
");

$stmt->bind_param("s",$email);
$stmt->execute();

$result = $stmt->get_result();

$total = 0;

?>

<!DOCTYPE html>
<html>
<head>

<title>LuxuryVault Cart</title>

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

.container{
width:90%;
max-width:1100px;
margin:40px auto;
}

h1{
color:gold;
margin-bottom:30px;
}

.item{
display:flex;
align-items:center;
gap:20px;
background:#111;
padding:20px;
margin-bottom:20px;
border-radius:12px;
border:1px solid #222;
}

.item img{
width:120px;
height:120px;
object-fit:cover;
border-radius:10px;
}

.details{
flex:1;
}

.details h2{
color:gold;
margin-bottom:10px;
}

.details p{
margin:8px 0;
}

.qty{
margin-top:15px;
}

.qty a{
text-decoration:none;
}

.qty button{
padding:8px 15px;
font-size:18px;
cursor:pointer;
}

.removeBtn{
background:#c62828;
color:white;
border:none;
padding:10px 20px;
cursor:pointer;
border-radius:6px;
margin-top:15px;
}

.removeBtn:hover{
background:#a00000;
}

.checkout{
margin-top:30px;
}

.checkout h2{
margin-bottom:20px;
color:gold;
}

.checkout button{
padding:15px 35px;
background:gold;
color:black;
border:none;
font-weight:bold;
cursor:pointer;
border-radius:6px;
margin-right:15px;
}

.checkout button:hover{
background:#d4af37;
}

.empty{
background:#111;
padding:40px;
border-radius:12px;
text-align:center;
}

</style>

</head>

<body>

<div class="container">

<h1>Your Shopping Cart</h1>

<?php

if($result->num_rows > 0){

while($row = $result->fetch_assoc()){

$subtotal = $row['price'] * $row['quantity'];

$total += $subtotal;

$image = !empty($row['image']) && file_exists("images/".$row['image'])
?
"images/".$row['image']
:
"https://via.placeholder.com/120";

?>

<div class="item">

<img
src="<?php echo htmlspecialchars($image); ?>"
alt="<?php echo htmlspecialchars($row['name']); ?>">

<div class="details">

<h2><?php echo htmlspecialchars($row['name']); ?></h2>

<p>
Price:
TZS <?php echo number_format($row['price']); ?>
</p>

<p>
Quantity:
<?php echo $row['quantity']; ?>
</p>

<p>
Subtotal:
TZS <?php echo number_format($subtotal); ?>
</p>

<div class="qty">

<a href="decrease_quantity.php?id=<?php echo (int)$row['product_id']; ?>">
<button>-</button>
</a>

<a href="increase_quantity.php?id=<?php echo (int)$row['product_id']; ?>">
<button>+</button>
</a>

</div>

<a href="remove_from_cart.php?id=<?php echo (int)$row['product_id']; ?>">

<button class="removeBtn">
Remove
</button>

</a>

</div>

</div>

<?php

}

?>

<div class="checkout">

<h2>

Grand Total:
TZS <?php echo number_format($total); ?>

</h2>

<a href="checkout.php">

<button>
Proceed To Checkout
</button>

</a>

<a href="products.php">

<button>
Continue Shopping
</button>

</a>

</div>

<?php

}else{

?>

<div class="empty">

<h2>Your cart is empty.</h2>

<br>

<a href="products.php">

<button style="padding:15px 30px;background:gold;border:none;border-radius:6px;font-weight:bold;cursor:pointer;">

Browse Products

</button>

</a>

</div>

<?php

}

?>

</div>

</body>

</html>