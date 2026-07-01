<?php

session_set_cookie_params([
    'httponly' => true,
    'samesite' => 'Lax'
]);

session_start();

if(!isset($_SESSION['email'])){
    header("Location: login.php");
    exit();
}

if($_SERVER['REQUEST_METHOD'] != 'POST'){
    header("Location: checkout.php");
    exit();
}

$customer_name = htmlspecialchars($_POST['customer_name']);
$phone = htmlspecialchars($_POST['phone']);
$address = htmlspecialchars($_POST['address']);
$payment_method = htmlspecialchars($_POST['payment_method']);
$total = (float)$_POST['total'];

?>

<!DOCTYPE html>
<html>
<head>

<title>LuxuryVault Payment Sandbox</title>

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
display:flex;
justify-content:center;
align-items:center;
height:100vh;
}

.container{
width:500px;
background:#111;
padding:40px;
border-radius:15px;
border:1px solid #222;
}

h1{
text-align:center;
color:gold;
margin-bottom:30px;
}

.info{
background:#1a1a1a;
padding:15px;
border-radius:10px;
margin-bottom:25px;
line-height:1.8;
}

.info strong{
color:gold;
}

input{
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
color:black;
border:none;
font-weight:bold;
font-size:16px;
cursor:pointer;
border-radius:8px;
}

button:hover{
background:#d4af37;
}

.note{
margin-top:20px;
text-align:center;
color:#999;
font-size:14px;
}

#loading{
display:none;
text-align:center;
margin-top:20px;
font-size:18px;
color:gold;
}

</style>

<script>

function processPayment(){

let pin=document.getElementById("pin").value;

if(pin!="1234"){

alert("Invalid Sandbox PIN.\n\nUse PIN: 1234");

return false;

}

document.getElementById("payBtn").style.display="none";

document.getElementById("loading").style.display="block";

setTimeout(function(){

document.getElementById("paymentForm").submit();

},3000);

return false;

}

</script>

</head>

<body>

<div class="container">

<h1>Payment Sandbox</h1>

<div class="info">

<p><strong>Customer:</strong> <?php echo $customer_name; ?></p>

<p><strong>Payment:</strong> <?php echo $payment_method; ?></p>

<p><strong>Amount:</strong> TZS <?php echo number_format($total); ?></p>

</div>

<form
id="paymentForm"
action="place_order.php"
method="POST">

<input
type="hidden"
name="customer_name"
value="<?php echo $customer_name; ?>">

<input
type="hidden"
name="phone"
value="<?php echo $phone; ?>">

<input
type="hidden"
name="address"
value="<?php echo $address; ?>">

<input
type="hidden"
name="payment_method"
value="<?php echo $payment_method; ?>">

<input
type="hidden"
name="total"
value="<?php echo $total; ?>">

<input
type="hidden"
name="payment_status"
value="Paid">

<input
type="hidden"
name="payment_reference"
value="<?php echo 'LV'.date('YmdHis').rand(100,999); ?>">

<input
type="text"
placeholder="Phone Number"
value="<?php echo $phone; ?>"
readonly>

<input
type="password"
id="pin"
placeholder="Enter Sandbox PIN"
maxlength="4"
required>

<button
id="payBtn"
onclick="return processPayment();">

Confirm Payment

</button>

</form>

<div id="loading">

Connecting to <?php echo $payment_method; ?>...<br><br>

Authorizing Payment...<br><br>

Processing Transaction...<br><br>

<b>Payment Successful ✓</b>

</div>

<div class="note">

<b>Sandbox Mode</b><br><br>

Use PIN:<br>

<b>1234</b>

</div>

</div>

</body>
</html>