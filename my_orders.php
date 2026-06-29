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

$email = $_SESSION['email'];

$query = mysqli_query($conn,
"SELECT * FROM orders
WHERE user_email='$email'
ORDER BY order_date DESC");
?>

<!DOCTYPE html>
<html>
<head>
<title>My Orders</title>

<style>

body{
background:#0b0b0b;
color:white;
font-family:Poppins,sans-serif;
}

.container{
width:90%;
margin:auto;
padding:40px;
}

h1{
color:gold;
margin-bottom:30px;
}

.order{
background:#111;
padding:25px;
margin-bottom:20px;
border-radius:15px;
border:1px solid #222;
}

.status{
color:gold;
font-weight:bold;
}

</style>

</head>
<body>

<div class="container">

<h1>My Orders</h1>

<?php while($row=mysqli_fetch_assoc($query)){ ?>

<div class="order">

<h2><?php echo $row['customer_name']; ?></h2>

<p>
Phone:
<?php echo $row['phone']; ?>
</p>

<p>
Address:
<?php echo $row['address']; ?>
</p>

<p>
Payment:
<?php echo $row['payment_method']; ?>
</p>

<p>
Total:
TZS <?php echo number_format($row['total']); ?>
</p>

<p class="status">
Status:
<?php echo $row['status']; ?>
</p>

<p>
Date:
<?php echo $row['order_date']; ?>
</p>

</div>

<?php } ?>

</div>

</body>
</html>