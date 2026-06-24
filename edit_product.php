<?php
session_start();
include("connect.php");

$id = $_GET['id'];

// UPDATE PRODUCT
if(isset($_POST['updateProduct'])){

    $name = $_POST['name'];
    $category = $_POST['category'];
    $price = $_POST['price'];
    $description = $_POST['description'];
    $image = $_POST['image'];

    mysqli_query($conn,
    "UPDATE products SET

    name='$name',
    category='$category',
    price='$price',
    description='$description',
    image='$image'

    WHERE id='$id'");

    header("Location: manage_products.php");
    exit();
}

// GET PRODUCT DETAILS
$query = mysqli_query($conn,
"SELECT * FROM products WHERE id='$id'");

$product = mysqli_fetch_assoc($query);

?>

<!DOCTYPE html>
<html>
<head>
<title>Edit Product</title>

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

/* SIDEBAR */

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
text-decoration:none;
color:#ccc;
margin-bottom:25px;
font-size:18px;
}

.sidebar a:hover{
color:gold;
}

/* MAIN */

.main{
flex:1;
padding:40px;
}

.title{
color:gold;
margin-bottom:30px;
}

.form-box{
background:#111;
padding:30px;
border-radius:15px;
border:1px solid #222;
max-width:700px;
}

input,
textarea{
width:100%;
padding:15px;
margin-bottom:20px;
background:#1a1a1a;
border:1px solid #333;
color:white;
border-radius:8px;
}

textarea{
height:120px;
resize:none;
}

button{
width:100%;
padding:15px;
background:gold;
color:black;
font-weight:bold;
border:none;
border-radius:8px;
cursor:pointer;
}

button:hover{
background:#d4af37;
}

</style>

</head>
<body>

<div class="sidebar">

<div class="logo">LuxuryVault</div>

<a href="admin.php">Dashboard</a>
<a href="add_product.php">Add Product</a>
<a href="manage_products.php">Manage Products</a>
<a href="view_orders.php">Orders</a>
<a href="index.php">Website</a>
<a href="logout.php">Logout</a>

</div>

<div class="main">

<h1 class="title">Edit Product</h1>

<div class="form-box">

<form method="POST">

<input
type="text"
name="name"
value="<?php echo $product['name']; ?>"
required>

<input
type="text"
name="category"
value="<?php echo $product['category']; ?>"
required>

<input
type="number"
name="price"
value="<?php echo $product['price']; ?>"
required>

<input
type="text"
name="image"
value="<?php echo $product['image']; ?>"
required>

<textarea
name="description"
required><?php echo $product['description']; ?></textarea>

<button name="updateProduct">
Update Product
</button>

</form>

</div>

</div>

</body>
</html>