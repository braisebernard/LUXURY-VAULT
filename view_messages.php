<?php

include("admin_auth.php");

// Secure Query
$stmt = $conn->prepare(
"SELECT * FROM contacts
ORDER BY created_at DESC");

$stmt->execute();

$query = $stmt->get_result();

$totalMessages = $query->num_rows;

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>LuxuryVault | Customer Messages</title>

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

/* NAVBAR */

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

/* PAGE */

.container{
width:90%;
margin:40px auto;
}

.page-title{
text-align:center;
margin-bottom:30px;
}

.page-title h1{
font-size:40px;
color:gold;
}

.summary{
background:#111;
padding:20px;
border-radius:10px;
margin-bottom:30px;
border:1px solid #222;
font-size:20px;
}

.summary span{
color:gold;
font-weight:bold;
}

.message-card{
background:#111;
padding:25px;
border-radius:12px;
border:1px solid #222;
margin-bottom:25px;
}

.message-card h3{
color:gold;
margin-bottom:15px;
}

.message-card p{
margin:10px 0;
line-height:1.7;
}

.label{
font-weight:bold;
color:#ccc;
}

.date{
margin-top:15px;
font-size:14px;
color:#888;
}

.no-messages{
background:#111;
padding:40px;
text-align:center;
border-radius:12px;
border:1px solid #222;
font-size:18px;
color:#999;
}

.back{
text-align:center;
margin:40px;
}

.back a{
display:inline-block;
padding:12px 25px;
background:gold;
color:black;
text-decoration:none;
font-weight:bold;
border-radius:8px;
}

.back a:hover{
background:#d4af37;
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

<a href="view_orders.php">Orders</a>

<a href="view_users.php">Users</a>

<a href="logout.php">Logout</a>

</div>

</div>

<div class="container">

<div class="page-title">

<h1>Customer Messages</h1>

</div>

<div class="summary">

Total Messages:

<span>

<?php echo $totalMessages; ?>

</span>

</div>

<?php

if($totalMessages > 0){

while($row = $query->fetch_assoc()){

?>

<div class="message-card">

<h3>

Message #<?php echo (int)$row['id']; ?>

</h3>

<p>

<span class="label">

Name:

</span>

<?php echo htmlspecialchars($row['name']); ?>

</p>

<p>

<span class="label">

Email:

</span>

<?php echo htmlspecialchars($row['email']); ?>

</p>

<p>

<span class="label">

Message:

</span>

<br><br>

<?php echo nl2br(htmlspecialchars($row['message'])); ?>

</p>

<p class="date">

Received:

<?php echo htmlspecialchars($row['created_at']); ?>

</p>

</div>

<?php

}

}else{

?>

<div class="no-messages">

No customer messages found.

</div>

<?php

}

?>

<div class="back">

<a href="admin.php">

← Back To Dashboard

</a>

</div>

</div>

</body>

</html>