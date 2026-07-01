<?php

include("admin_auth.php");
include("connect.php");

// Validate Product ID
if(!isset($_GET['id']) || !is_numeric($_GET['id'])){
    header("Location: manage_products.php");
    exit();
}

$id = (int)$_GET['id'];

// Get product
$stmt = $conn->prepare("SELECT * FROM products WHERE id=?");
$stmt->bind_param("i",$id);
$stmt->execute();

$result = $stmt->get_result();

if($result->num_rows==0){
    header("Location: manage_products.php");
    exit();
}

$product = $result->fetch_assoc();


// UPDATE PRODUCT
if(isset($_POST['updateProduct'])){

    $name = trim($_POST['name']);
    $category = trim($_POST['category']);
    $price = (float)$_POST['price'];
    $description = trim($_POST['description']);

    $imageName = $product['image'];

    // New image uploaded
    if(isset($_FILES['image']) && $_FILES['image']['error']==0){

        $allowed = ['jpg','jpeg','png','webp'];

        $extension = strtolower(
            pathinfo($_FILES['image']['name'],PATHINFO_EXTENSION)
        );

        if(in_array($extension,$allowed)){

            // Delete old image
            if(!empty($product['image']) &&
               file_exists("images/".$product['image'])){
                unlink("images/".$product['image']);
            }

            $imageName = uniqid().".".$extension;

            move_uploaded_file(
                $_FILES['image']['tmp_name'],
                "images/".$imageName
            );

        }

    }

    $stmt = $conn->prepare(
    "UPDATE products
    SET
    name=?,
    category=?,
    price=?,
    description=?,
    image=?
    WHERE id=?");

    $stmt->bind_param(
        "ssdssi",
        $name,
        $category,
        $price,
        $description,
        $imageName,
        $id
    );

    if($stmt->execute()){

        header("Location: manage_products.php");
        exit();

    }

}

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

img{
width:180px;
border-radius:10px;
margin-bottom:20px;
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
<a href="view_users.php">Users</a>
<a href="view_messages.php">Messages</a>
<a href="logout.php">Logout</a>

</div>

<div class="main">

<h1 class="title">Edit Product</h1>

<div class="form-box">

<form method="POST" enctype="multipart/form-data">

<img
src="images/<?php echo htmlspecialchars($product['image']); ?>"
alt="Product Image">

<input
type="text"
name="name"
value="<?php echo htmlspecialchars($product['name']); ?>"
required>

<input
type="text"
name="category"
value="<?php echo htmlspecialchars($product['category']); ?>"
required>

<input
type="number"
step="0.01"
name="price"
value="<?php echo htmlspecialchars($product['price']); ?>"
required>

<label>Replace Image (Optional)</label>

<input
type="file"
name="image"
accept=".jpg,.jpeg,.png,.webp">

<textarea
name="description"
required><?php echo htmlspecialchars($product['description']); ?></textarea>

<button
type="submit"
name="updateProduct">

Update Product

</button>

</form>

</div>

</div>

</body>

</html>