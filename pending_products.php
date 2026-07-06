<?php

include("product_auth.php");

if(!$isAdmin){
    die("Access Denied.");
}

$stmt = $conn->prepare("

SELECT

products.*,

users.shop_name,

users.firstName,

users.lastName

FROM products

LEFT JOIN users

ON products.seller_id = users.id

WHERE products.status='pending'

ORDER BY products.id DESC

");

$stmt->execute();

$query = $stmt->get_result();

?>
<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
content="width=device-width, initial-scale=1.0">

<title>Pending Products</title>

<style>

*{
margin:0;
padding:0;
box-sizing:border-box;
font-family:Poppins,sans-serif;
}

body{
display:flex;
background:#0b0b0b;
color:white;
}

/* Sidebar */

.sidebar{
width:250px;
min-height:100vh;
background:black;
padding:30px;
border-right:1px solid #222;
}

.logo{
font-size:30px;
font-weight:bold;
color:gold;
margin-bottom:45px;
}

.sidebar a{
display:block;
text-decoration:none;
color:#ccc;
margin-bottom:20px;
font-size:18px;
transition:.3s;
}

.sidebar a:hover{
color:gold;
}

/* Main */

.main{
flex:1;
padding:40px;
}

.title{
color:gold;
margin-bottom:35px;
}

.card{

background:#111;
border:1px solid #222;
border-radius:15px;
padding:25px;
margin-bottom:30px;

display:grid;
grid-template-columns:250px 1fr;
gap:25px;

}

.card img{

width:100%;
height:250px;
object-fit:cover;
border-radius:12px;

}

.info h2{

color:gold;
margin-bottom:15px;

}

.info p{

margin-bottom:10px;
line-height:1.7;

}

.label{

color:#999;
font-weight:bold;

}

.buttons{

margin-top:25px;

}

.approve{

background:#2e7d32;
color:white;
padding:12px 20px;
text-decoration:none;
border-radius:8px;
margin-right:10px;
font-weight:bold;

}

.reject{

background:#c62828;
color:white;
padding:12px 20px;
text-decoration:none;
border-radius:8px;
font-weight:bold;

}

.empty{

background:#111;
padding:60px;
text-align:center;
border-radius:15px;
border:1px solid #222;
font-size:22px;
color:#888;

}

</style>

</head>

<body>

<div class="sidebar">

<div class="logo">

LuxuryVault

</div>

<a href="admin.php">Dashboard</a>

<a href="pending_products.php">Pending Products</a>

<a href="manage_products.php">Manage Products</a>

<a href="seller_requests.php">Seller Requests</a>

<a href="view_orders.php">Orders</a>

<a href="view_users.php">Users</a>

<a href="view_messages.php">Messages</a>

<a href="logout.php">Logout</a>

</div>
<div class="main">

<h1 class="title">

Pending Product Approvals

</h1>

<?php

if($query->num_rows>0){

while($row=$query->fetch_assoc()){

?>

<div class="card">

<div>

<img
src="images/<?php echo htmlspecialchars($row['image']); ?>">

</div>

<div class="info">

<h2>

<?php echo htmlspecialchars($row['name']); ?>

</h2>

<p>

<span class="label">

Seller:

</span>

<?php

echo htmlspecialchars(

$row['shop_name'] ??
($row['firstName']." ".$row['lastName'])

);

?>

</p>

<p>

<span class="label">

Category:

</span>

<?php echo htmlspecialchars($row['category']); ?>

</p>

<p>

<span class="label">

Price:

</span>

TZS <?php echo number_format($row['price']); ?>

</p>

<p>

<span class="label">

Stock:

</span>

<?php echo (int)$row['quantity']; ?>

</p>

<p>

<span class="label">

Description:

</span>

<br>

<?php echo nl2br(htmlspecialchars($row['description'])); ?>

</p>

<div class="buttons">

<a
class="approve"
href="approve_product.php?id=<?php echo (int)$row['id']; ?>">

Approve

</a>

<a
class="reject"
href="reject_product.php?id=<?php echo (int)$row['id']; ?>">

Reject

</a>

</div>

</div>

</div>

<?php

}

}else{

?>

<div class="empty">

🎉 No pending products.

</div>

<?php

}

?>

</div>

</body>

</html>