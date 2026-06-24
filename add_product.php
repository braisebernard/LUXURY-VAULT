<?php
session_start();
include("connect.php");

if(isset($_POST['addProduct'])){

    $name = $_POST['name'];
    $category = $_POST['category'];
    $price = $_POST['price'];
    $description = $_POST['description'];
    $image = $_POST['image'];

    $sql = "INSERT INTO products(name,category,price,description,image)
            VALUES('$name','$category','$price','$description','$image')";

    if(mysqli_query($conn,$sql)){
        echo "<script>alert('Product Added Successfully');</script>";
    }else{
        echo "<script>alert('Failed To Add Product');</script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Add Product</title>

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
text-decoration:none;
color:#ccc;
margin-bottom:25px;
font-size:18px;
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

<h1 class="title">Add New Product</h1>

<div class="form-box">

<form method="POST">

<input type="text"
name="name"
placeholder="Product Name"
required>

<input type="text"
name="category"
placeholder="Category"
required>

<input type="number"
name="price"
placeholder="Price"
required>

<input type="text"
name="image"
placeholder="Image Filename (example rolex.jpg)"
required>

<textarea
name="description"
placeholder="Product Description"
required></textarea>

<button name="addProduct">
Add Product
</button>

</form>

</div>

</div>

</body>
</html>