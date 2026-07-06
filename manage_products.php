<?php

include("product_auth.php");

/*
==================================
FETCH PRODUCTS
==================================
*/

if($isAdmin){

    $stmt = $conn->prepare("

    SELECT
    products.*,
    users.shop_name

    FROM products

    LEFT JOIN users

    ON products.seller_id = users.id

    ORDER BY id DESC

    ");

}else{

    $sellerId = $_SESSION['user_id'];

    $stmt = $conn->prepare("

    SELECT
    products.*

    FROM products

    WHERE seller_id=?

    ORDER BY id DESC

    ");

    $stmt->bind_param("i",$sellerId);

}

$stmt->execute();

$query = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
content="width=device-width, initial-scale=1.0">

<title>Manage Products</title>

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
color:#ccc;
text-decoration:none;
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
margin-bottom:30px;

}

table{

width:100%;
border-collapse:collapse;
background:#111;
border-radius:15px;
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

img{

width:80px;
height:80px;
object-fit:cover;
border-radius:10px;

}

.status{

padding:8px 15px;
border-radius:20px;
font-size:14px;
font-weight:bold;
display:inline-block;

}

.approved{

background:#1b5e20;
color:white;

}

.pending{

background:#ff9800;
color:black;

}

.rejected{

background:#b71c1c;
color:white;

}

.edit{

background:gold;
color:black;
padding:10px 15px;
border-radius:6px;
text-decoration:none;
margin-right:10px;
font-weight:bold;

}

.delete{

background:#d32f2f;
color:white;
padding:10px 15px;
border-radius:6px;
text-decoration:none;
font-weight:bold;

}

.edit:hover{

background:#d4af37;

}

.delete:hover{

background:red;

}

</style>

</head>

<body>

<div class="sidebar">

<div class="logo">

LuxuryVault

</div>

<?php if($isAdmin){ ?>

<a href="admin.php">Dashboard</a>

<a href="add_product.php">Add Product</a>

<a href="manage_products.php">Manage Products</a>

<a href="seller_requests.php">Seller Requests</a>

<a href="view_orders.php">Orders</a>

<a href="view_users.php">Users</a>

<a href="view_messages.php">Messages</a>

<?php }else{ ?>

<a href="seller_dashboard.php">Dashboard</a>

<a href="add_product.php">Add Product</a>

<a href="manage_products.php">My Products</a>

<a href="seller_orders.php">Orders</a>

<a href="seller_profile.php">Store Profile</a>

<?php } ?>

<a href="index.php">Website</a>

<a href="logout.php">Logout</a>

</div>

<div class="main">
<div class="main">

<?php if($isAdmin){ ?>

<h1 class="title">Manage Products</h1>

<?php }else{ ?>

<h1 class="title">My Products</h1>

<?php } ?>

<table>

<tr>

<th>Image</th>

<th>Name</th>

<th>Category</th>

<th>Price</th>

<th>Stock</th>

<?php if($isAdmin){ ?>

<th>Seller</th>

<?php } ?>

<th>Status</th>

<th>Action</th>

</tr>

<?php while($row = $query->fetch_assoc()){ ?>

<tr>

<td>

<img src="images/<?php echo htmlspecialchars($row['image']); ?>">

</td>

<td>

<?php echo htmlspecialchars($row['name']); ?>

</td>

<td>

<?php echo htmlspecialchars($row['category']); ?>

</td>

<td>

TZS <?php echo number_format($row['price']); ?>

</td>

<td>

<?php echo (int)$row['quantity']; ?>

</td>

<?php if($isAdmin){ ?>

<td>

<?php

if(empty($row['seller_id'])){

    echo "<span style='color:gold;'>LuxuryVault</span>";

}else{

    echo htmlspecialchars($row['shop_name']);

}

?>

</td>

<?php } ?>

<td>

<?php

$status = strtolower($row['status']);

$class = "approved";

if($status=="pending"){

    $class="pending";

}elseif($status=="rejected"){

    $class="rejected";

}

?>

<span class="status <?php echo $class; ?>">

<?php echo ucfirst($status); ?>

</span>

</td>

<td>

<a

class="edit"

href="edit_product.php?id=<?php echo (int)$row['id']; ?>">

Edit

</a>

<a

class="delete"

href="delete_product.php?id=<?php echo (int)$row['id']; ?>"

onclick="return confirm('Delete this product?')">

Delete

</a>

</td>

</tr>

<?php } ?>

<?php if($query->num_rows==0){ ?>

<tr>

<td colspan="<?php echo $isAdmin ? 8 : 7; ?>"

style="text-align:center;color:#999;padding:40px;">

No products found.

</td>

</tr>

<?php } ?>

</table>

</div>

</body>

</html>
