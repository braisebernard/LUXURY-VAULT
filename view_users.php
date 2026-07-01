<?php

include("admin_auth.php");

// Secure Query
$stmt = $conn->prepare("SELECT * FROM users ORDER BY id DESC");
$stmt->execute();

$query = $stmt->get_result();

$totalUsers = $query->num_rows;

?>

<!DOCTYPE html>
<html>
<head>

<title>LuxuryVault | Registered Users</title>

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

.title{
text-align:center;
margin-bottom:30px;
}

.title h1{
color:gold;
font-size:40px;
}

.summary{
background:#111;
padding:20px;
border-radius:10px;
margin-bottom:30px;
font-size:20px;
border:1px solid #222;
}

.summary span{
color:gold;
font-weight:bold;
}

table{
width:100%;
border-collapse:collapse;
background:#111;
border-radius:12px;
overflow:hidden;
}

th{
background:gold;
color:black;
padding:18px;
text-align:left;
}

td{
padding:18px;
border-bottom:1px solid #222;
}

tr:hover{
background:#1b1b1b;
}

.badge-admin{
background:green;
padding:6px 12px;
border-radius:20px;
font-size:14px;
}

.badge-user{
background:#444;
padding:6px 12px;
border-radius:20px;
font-size:14px;
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

<a href="view_messages.php">Messages</a>

<a href="logout.php">Logout</a>

</div>

</div>

<div class="container">

<div class="title">

<h1>Registered Users</h1>

</div>

<div class="summary">

Total Registered Users:

<span>

<?php echo $totalUsers; ?>

</span>

</div>

<table>

<tr>

<th>ID</th>

<th>First Name</th>

<th>Last Name</th>

<th>Email</th>

<th>Role</th>

</tr>

<?php while($row = $query->fetch_assoc()){ ?>

<tr>

<td>

<?php echo (int)$row['id']; ?>

</td>

<td>

<?php echo htmlspecialchars($row['firstName']); ?>

</td>

<td>

<?php echo htmlspecialchars($row['lastName']); ?>

</td>

<td>

<?php echo htmlspecialchars($row['email']); ?>

</td>

<td>

<?php

if($row['is_admin']==1){

echo "<span class='badge-admin'>Admin</span>";

}else{

echo "<span class='badge-user'>Customer</span>";

}

?>

</td>

</tr>

<?php } ?>

</table>

<div class="back">

<a href="admin.php">

← Back To Dashboard

</a>

</div>

</div>

</body>

</html>