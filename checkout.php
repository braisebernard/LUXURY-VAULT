<?php
session_set_cookie_params([
    'httponly' => true,
    'samesite' => 'Lax'
]);

session_start();
include("connect.php");

if(!isset($_SESSION['email'])){
    header("Location: login.php");
    exit();
}

$total = 0;

$email = $_SESSION['email'];

$query = mysqli_query($conn,
"SELECT cart.*, products.*
FROM cart
JOIN products ON cart.product_id = products.id
WHERE cart.user_email='$email'");

while($row=mysqli_fetch_assoc($query)){
    $total += $row['price'] * $row['quantity'];
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Checkout</title>

<style>

body{
background:#0b0b0b;
color:white;
font-family:Poppins,sans-serif;
}

.container{
width:600px;
margin:50px auto;
background:#111;
padding:40px;
border-radius:15px;
}

h1{
color:gold;
margin-bottom:30px;
}

input,select{
width:100%;
padding:15px;
margin-bottom:20px;
background:#1a1a1a;
border:1px solid #333;
color:white;
border-radius:8px;
}

button{
width:100%;
padding:15px;
background:gold;
border:none;
font-weight:bold;
cursor:pointer;
border-radius:8px;
}

.total{
font-size:24px;
color:gold;
margin-bottom:25px;
}

</style>

</head>
<body>

<div class="container">

<h1>Checkout</h1>

<div class="total">
Total: TZS <?php echo number_format($total); ?>
</div>

<form action="place_order.php" method="POST">

<input type="text"
name="customer_name"
placeholder="Full Name"
required>

<input type="text"
name="phone"
placeholder="Phone Number"
required>

<input type="text"
name="address"
placeholder="Delivery Address"
required>

<select name="payment_method">

<option>M-Pesa</option>
<option>Airtel Money</option>
<option>Tigo Pesa</option>
<option>Cash On Delivery</option>

</select>

<input type="hidden"
name="total"
value="<?php echo $total; ?>">

<button>
Place Order
</button>

</form>

</div>

</body>
</html>