<?php

include("admin_auth.php");

// Secure product query
$stmt = $conn->prepare("SELECT * FROM products ORDER BY id DESC");
$stmt->execute();

$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html>
<head>

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
margin-bottom:50px;
}

.sidebar a{
display:block;
color:#ccc;
text-decoration:none;
margin-bottom:25px;
font-size:18px;
transition:.3s;
}

.sidebar a:hover{
color:gold;
}

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
vertical-align:middle;
}

img{
width:80px;
height:80px;
object-fit:cover;
border-radius:10px;
}

.edit-btn{
background:gold;
color:black;
padding:10px 18px;
text-decoration:none;
border-radius:6px;
margin-right:10px;
font-weight:bold;
}

.delete-btn{
background:#c62828;
color:white;
padding:10px 18px;
text-decoration:none;
border-radius:6px;
font-weight:bold;
}

.delete-btn:hover{
background:red;
}

.empty{
padding:40px;
text-align:center;
font-size:20px;
color:#999;
}

.back-btn{
display:inline-block;
margin-top:30px;
padding:12px 25px;
background:#333;
color:white;
text-decoration:none;
border-radius:8px;
}

.back-btn:hover{
background:#555;
}

</style>

</head>

<body>

<div class="sidebar">

<div class="logo">
LuxuryVault
</div>

<a href="admin.php">Dashboard</a>
<a href="add_product.php">Add Product</a>
<a href="manage_products.php">Manage Products</a>
<a href="view_orders.php">Orders</a>
<a href="view_users.php">Users</a>
<a href="view_messages.php">Messages</a>
<a href="index.php">Website</a>
<a href="logout.php">Logout</a>

</div>

<div class="main">

<h1 class="title">
Manage Products
</h1>

<?php if($result->num_rows > 0){ ?>

<table>

<tr>

<th>Image</th>
<th>Name</th>
<th>Category</th>
<th>Price</th>
<th>Action</th>

</tr>

<?php while($row = $result->fetch_assoc()){ ?>

<?php

$image = (!empty($row['image']) && file_exists("images/".$row['image']))
?
"images/".$row['image']
:
"https://via.placeholder.com/80";

?>

<tr>

<td>

<img
src="<?php echo htmlspecialchars($image); ?>"
alt="<?php echo htmlspecialchars($row['name']); ?>">

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

<a
class="edit-btn"
href="edit_product.php?id=<?php echo (int)$row['id']; ?>">

Edit

</a>

<a
class="delete-btn"
href="delete_product.php?id=<?php echo (int)$row['id']; ?>"
onclick="return confirm('Delete this product?')">

Delete

</a>

</td>

</tr>

<?php } ?>

</table>

<?php }else{ ?>

<div class="empty">

No products found.

</div>

<?php } ?>

<a href="admin.php" class="back-btn">

← Back To Dashboard

</a>

</div>

</body>
</html>