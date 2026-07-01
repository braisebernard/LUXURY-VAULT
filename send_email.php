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

if(
    !isset($_GET['customer']) ||
    !isset($_GET['payment']) ||
    !isset($_GET['payment_status']) ||
    !isset($_GET['reference']) ||
    !isset($_GET['total']) ||
    !isset($_GET['status'])
){
    header("Location:index.php");
    exit();
}

$email = $_SESSION['email'];

$customer = htmlspecialchars($_GET['customer']);
$payment = htmlspecialchars($_GET['payment']);
$paymentStatus = htmlspecialchars($_GET['payment_status']);
$reference = htmlspecialchars($_GET['reference']);
$total = (float)$_GET['total'];
$status = htmlspecialchars($_GET['status']);

?>

<!DOCTYPE html>
<html>

<head>

<title>LuxuryVault | Email Confirmation</title>

<style>

*{
margin:0;
padding:0;
box-sizing:border-box;
font-family:Poppins,sans-serif;
}

body{
background:#0b0b0b;
display:flex;
justify-content:center;
align-items:center;
min-height:100vh;
padding:40px;
color:white;
}

.container{

width:750px;
background:#111;
border:1px solid #222;
border-radius:15px;
overflow:hidden;

}

.header{

background:gold;
color:black;
padding:25px;
font-size:28px;
font-weight:bold;
text-align:center;

}

.success{

background:#0f5132;
padding:15px;
text-align:center;
font-size:20px;

}

.email{

padding:35px;
line-height:2;

}

.email h2{

color:gold;
margin-bottom:20px;

}

.email strong{

color:gold;

}

.reference{

font-family:monospace;
font-size:18px;
color:#4FC3F7;

}

.buttons{

padding:30px;
text-align:center;

}

button{

padding:15px 30px;
background:gold;
border:none;
border-radius:8px;
cursor:pointer;
font-weight:bold;
margin:10px;

}

button:hover{

background:#d4af37;

}

.footer{

padding:20px;
text-align:center;
background:#1a1a1a;
color:#888;

}

</style>

</head>

<body>

<div class="container">

<div class="header">

LuxuryVault Email Service

</div>

<div class="success">

📧 Order Confirmation Email Generated Successfully

</div>

<div class="email">

<p>

<strong>To:</strong>

<?php echo $email; ?>

</p>

<p>

<strong>Subject:</strong>

LuxuryVault Order Confirmation

</p>

<hr style="margin:20px 0;border:1px solid #333;">

<h2>

Dear <?php echo $customer; ?>,

</h2>

<p>

Thank you for shopping with <strong>LuxuryVault</strong>.

</p>

<p>

Your payment has been received successfully.

</p>

<p>

<strong>Payment Method:</strong>

<?php echo $payment; ?>

</p>

<p>

<strong>Payment Status:</strong>

<?php echo $paymentStatus; ?>

</p>

<p>

<strong>Payment Reference:</strong>

<span class="reference">

<?php echo $reference; ?>

</span>

</p>

<p>

<strong>Total Paid:</strong>

TZS <?php echo number_format($total); ?>

</p>

<p>

<strong>Order Status:</strong>

<?php echo $status; ?>

</p>

<p>

Your luxury items are now being prepared for delivery.

</p>

<p>

Thank you for choosing LuxuryVault.

</p>

<p>

<b>LuxuryVault Team</b>

</p>

</div>

<div class="buttons">

<a href="my_orders.php">

<button>

View My Orders

</button>

</a>

<a href="index.php">

<button>

Back To Homepage

</button>

</a>

</div>

<div class="footer">

Sandbox Email Preview (No real email was sent)

</div>

</div>

</body>

</html>