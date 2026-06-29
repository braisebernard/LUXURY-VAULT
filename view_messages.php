<?php
include("connect.php");
include("admin_auth.php");

// OPTIONAL ADMIN PROTECTION
if(!isset($_SESSION['email'])){
    header("Location: login.php");
    exit();
}

$query = mysqli_query($conn,
"SELECT * FROM contacts ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Customer Messages</title>

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
padding:20px 50px;
display:flex;
justify-content:space-between;
align-items:center;
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

.container{
width:90%;
margin:40px auto;
}

.page-title{
text-align:center;
margin-bottom:40px;
}

.page-title h1{
color:gold;
}

.message-card{
background:#111;
border:1px solid #222;
padding:25px;
border-radius:10px;
margin-bottom:20px;
}

.message-card h3{
color:gold;
margin-bottom:10px;
}

.message-card p{
margin:8px 0;
line-height:1.6;
}

.label{
font-weight:bold;
color:#ccc;
}

.date{
color:#999;
font-size:14px;
margin-top:10px;
}

.no-messages{
text-align:center;
padding:40px;
color:#888;
}

.footer{
text-align:center;
padding:20px;
background:black;
margin-top:40px;
color:#777;
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
<a href="view_orders.php">Orders</a>
<a href="view_users.php">Users</a>
<a href="logout.php">Logout</a>
</div>

</div>

<div class="container">

<div class="page-title">
<h1>Customer Messages</h1>
</div>

<?php

if(mysqli_num_rows($query) > 0){

while($row = mysqli_fetch_assoc($query)){

?>

<div class="message-card">

<h3>
Message #<?php echo $row['id']; ?>
</h3>

<p>
<span class="label">Name:</span>
<?php echo $row['name']; ?>
</p>

<p>
<span class="label">Email:</span>
<?php echo $row['email']; ?>
</p>

<p>
<span class="label">Message:</span><br>
<?php echo nl2br($row['message']); ?>
</p>

<p class="date">
Received:
<?php echo $row['created_at']; ?>
</p>

</div>

<?php

}

}else{

?>

<div class="no-messages">
No customer messages yet.
</div>

<?php } ?>

</div>

<div class="footer">
© 2026 LuxuryVault Admin Panel
</div>

</body>
</html>