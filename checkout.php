<?php

session_set_cookie_params([
    'httponly' => true,
    'samesite' => 'Lax'
]);

session_start();
include("config/connect.php");

// User must be logged in
if(!isset($_SESSION['email'])){
    header("Location: login.php");
    exit();
}

$email = $_SESSION['email'];

// Get cart securely
$stmt = $conn->prepare("
SELECT
products.price,
cart.quantity
FROM cart
INNER JOIN products
ON cart.product_id = products.id
WHERE cart.user_email = ?
");

$stmt->bind_param("s",$email);
$stmt->execute();

$result = $stmt->get_result();

$total = 0;

while($row = $result->fetch_assoc()){
    $total += $row['price'] * $row['quantity'];
}

// Empty cart
if($total <= 0){
    header("Location: cart.php");
    exit();
}

?>

<!DOCTYPE html>
<html>

<head>

<title>LuxuryVault Checkout</title>

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
max-width:650px;
margin:60px auto;
background:#111;
padding:40px;
border-radius:15px;
border:1px solid #222;
}

h1{
color:gold;
margin-bottom:30px;
text-align:center;
}

.total{
background:#1a1a1a;
padding:20px;
border-radius:10px;
margin-bottom:25px;
font-size:24px;
font-weight:bold;
color:gold;
text-align:center;
}

input,
select{
width:100%;
padding:15px;
margin-bottom:20px;
background:#1a1a1a;
border:1px solid #333;
color:white;
border-radius:8px;
font-size:16px;
}

button{
width:100%;
padding:15px;
border:none;
border-radius:8px;
cursor:pointer;
font-weight:bold;
font-size:16px;
margin-top:10px;
}

.placeBtn{
background:gold;
color:black;
}

.placeBtn:hover{
background:#d4af37;
}

.backBtn{
background:#333;
color:white;
margin-top:15px;
}

.backBtn:hover{
background:#555;
}

</style>

</head>

<body>

<div class="container">

<h1>Checkout</h1>

<div class="total">
Total: TZS <?php echo number_format($total); ?>
</div>

<form action="payment.php" method="POST">

<input
type="text"
name="customer_name"
placeholder="Full Name"
maxlength="100"
required>

<input
type="text"
name="phone"
placeholder="Phone Number"
maxlength="20"
required>

<input
type="text"
name="address"
placeholder="Delivery Address"
maxlength="255"
required>

<select name="payment_method" required>

<option value="M-Pesa">M-Pesa</option>

<option value="Airtel Money">Airtel Money</option>

<option value="Tigo Pesa">Tigo Pesa</option>

<option value="Cash On Delivery">Cash On Delivery</option>

</select>

<input
type="hidden"
name="total"
value="<?php echo (float)$total; ?>">

<button
type="submit"
class="placeBtn">
Place Order
</button>

</form>

<a href="cart.php">

<button class="backBtn">

Back To Cart

</button>

</a>

</div>

</body>

</html>