<?php

include("admin_auth.php");

// Get all orders
$stmt = $conn->prepare("SELECT * FROM orders ORDER BY order_date DESC");
$stmt->execute();
$query = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>LuxuryVault | Orders</title>

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
background:black;
display:flex;
justify-content:space-between;
align-items:center;
padding:20px 50px;
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
}

.nav-links a:hover{
color:gold;
}

.title{
text-align:center;
margin:40px 0;
}

.title h1{
color:gold;
font-size:40px;
}

.container{
width:90%;
margin:auto;
}

.order-card{
background:#111;
border:1px solid #222;
border-radius:12px;
padding:25px;
margin-bottom:25px;
}

.order-card h2{
color:gold;
margin-bottom:20px;
}

.order-card p{
margin:10px 0;
color:#ddd;
line-height:1.7;
}

.status{
color:gold;
font-weight:bold;
}

.payment-status{
color:#32CD32;
font-weight:bold;
}

.reference{
color:#4FC3F7;
font-weight:bold;
font-family:monospace;
}

form{
margin-top:20px;
}

select{
padding:10px;
background:#1a1a1a;
border:1px solid #333;
color:white;
border-radius:5px;
}

button{
padding:10px 20px;
background:gold;
color:black;
border:none;
font-weight:bold;
border-radius:5px;
cursor:pointer;
margin-left:10px;
}

button:hover{
background:#d4af37;
}

.success{
background:#0f5132;
padding:15px;
width:90%;
margin:20px auto;
text-align:center;
border-radius:10px;
}

.back{
text-align:center;
margin:40px;
}

.back a{
color:gold;
text-decoration:none;
font-weight:bold;
}

</style>

</head>

<body>

<div class="navbar">

<div class="logo">

LuxuryVault

</div>

<div class="nav-links">

<a href="admin.php">Dashboard</a>

<a href="manage_products.php">Products</a>

<a href="view_users.php">Users</a>

<a href="view_messages.php">Messages</a>

<a href="logout.php">Logout</a>

</div>

</div>

<div class="title">

<h1>Customer Orders</h1>

</div>

<?php if(isset($_GET['updated'])){ ?>

<div class="success">

Order Status Updated Successfully.

</div>

<?php } ?>

<div class="container">

<?php while($row=$query->fetch_assoc()){ ?>

<div class="order-card">

<h2>

Order #<?php echo (int)$row['id']; ?>

</h2>

<p>

<strong>Customer:</strong>

<?php echo htmlspecialchars($row['customer_name']); ?>

</p>

<p>

<strong>Email:</strong>

<?php echo htmlspecialchars($row['user_email']); ?>

</p>

<p>

<strong>Phone:</strong>

<?php echo htmlspecialchars($row['phone']); ?>

</p>

<p>

<strong>Delivery Address:</strong>

<?php echo htmlspecialchars($row['address']); ?>

</p>

<p>

<strong>Payment Method:</strong>

<?php echo htmlspecialchars($row['payment_method']); ?>

</p>

<p class="payment-status">

<strong>Payment Status:</strong>

<?php echo htmlspecialchars($row['payment_status']); ?>

</p>

<p class="reference">

<strong>Payment Reference:</strong>

<?php echo htmlspecialchars($row['payment_reference']); ?>

</p>

<p>

<strong>Total Paid:</strong>

TZS <?php echo number_format($row['total']); ?>

</p>

<p class="status">

<strong>Order Status:</strong>

<?php echo htmlspecialchars($row['status']); ?>

</p>

<p>

<strong>Order Date:</strong>

<?php echo htmlspecialchars($row['order_date']); ?>

</p>

<form action="update_order.php" method="GET">

<input
type="hidden"
name="id"
value="<?php echo (int)$row['id']; ?>">

<select name="status">

<option value="Pending"
<?php if($row['status']=="Pending") echo "selected"; ?>>
Pending
</option>

<option value="Processing"
<?php if($row['status']=="Processing") echo "selected"; ?>>
Processing
</option>

<option value="Delivered"
<?php if($row['status']=="Delivered") echo "selected"; ?>>
Delivered
</option>

<option value="Cancelled"
<?php if($row['status']=="Cancelled") echo "selected"; ?>>
Cancelled
</option>

</select>

<button type="submit">

Update Status

</button>

</form>

</div>

<?php } ?>

</div>

<div class="back">

<a href="admin.php">

← Back To Dashboard

</a>

</div>

</body>

</html>