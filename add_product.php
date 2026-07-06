<?php
include("product_auth.php");
if(isset($_POST['addProduct'])){

    $name = trim($_POST['name']);
    $category = trim($_POST['category']);
    $price = (float)$_POST['price'];
    $quantity = (int)$_POST['quantity'];
    $description = trim($_POST['description']);

    if(
        empty($name) ||
        empty($category) ||
        $price <= 0 ||
        $quantity < 0 ||
        empty($description)
    ){
        die("Please complete all fields.");
    }

    if(!isset($_FILES['image']) || $_FILES['image']['error'] != 0){
        die("Please upload a product image.");
    }

    $allowed = [
        "jpg",
        "jpeg",
        "png",
        "webp"
    ];

    $originalName = $_FILES['image']['name'];

    $tmp = $_FILES['image']['tmp_name'];

    $extension = strtolower(
        pathinfo($originalName,PATHINFO_EXTENSION)
    );

    if(!in_array($extension,$allowed)){
        die("Invalid image type.");
    }

    $image = uniqid("product_").".".$extension;

    if(!move_uploaded_file(
        $tmp,
        "images/".$image
    )){
        die("Failed to upload image.");
    }

    if($isAdmin){

        $sellerId = NULL;
        $status = "approved";

    }else{

        $sellerId = $_SESSION['user_id'];
        $status = "pending";

    }

    $stmt = $conn->prepare("

INSERT INTO products
(
name,
category,
price,
quantity,
description,
image,
seller_id,
status
)
VALUES
(?,?,?,?,?,?,?,?)

");

    $stmt->bind_param(

    "ssdissis",

    $name,

    $category,

    $price,

    $quantity,

    $description,

    $image,

    $sellerId,

    $status

    );

    if($stmt->execute()){

        if($isAdmin){

            echo "<script>

            alert('Product Added Successfully');

            window.location='manage_products.php';

            </script>";

        }else{

            echo "<script>

            alert('Product submitted for approval.');

            window.location='manage_products.php';

            </script>";

        }

        exit();

    }else{

        die($conn->error);

    }

}

?>
<!DOCTYPE html>
<html>

<head>

<meta charset="UTF-8">

<meta name="viewport"
content="width=device-width, initial-scale=1.0">

<title>Add Product</title>

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
display:flex;
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

.main{

flex:1;
padding:40px;

}

.title{

color:gold;
margin-bottom:35px;

}

.form-box{

max-width:850px;

background:#111;

padding:35px;

border-radius:15px;

border:1px solid #222;

}

input,
textarea{

width:100%;

padding:15px;

margin-bottom:20px;

background:#1a1a1a;

border:1px solid #333;

border-radius:8px;

color:white;

font-size:16px;

}

textarea{

height:140px;

resize:none;

}

label{

display:block;

margin-bottom:8px;

color:gold;

}

button{

width:100%;

padding:16px;

background:gold;

color:black;

font-size:18px;

font-weight:bold;

border:none;

border-radius:8px;

cursor:pointer;

}

button:hover{

background:#d4af37;

}

.row{

display:grid;

grid-template-columns:1fr 1fr;

gap:20px;

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
<a href="seller_requests.php">Seller Applications</a>
<a href="add_product.php">Add Product</a>
<a href="manage_products.php">Manage Products</a>
<a href="view_orders.php">Orders</a>
<a href="view_users.php">Customers</a>
<a href="view_messages.php">Messages</a>

<?php } else { ?>

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

<?php if($isAdmin){ ?>

<h1 class="title">
Add New Product
</h1>

<?php } else { ?>

<h1 class="title">
Submit Product For Approval
</h1>

<p style="color:#aaa;margin-bottom:25px;">
Products submitted by sellers must be approved by an administrator before they become visible to customers.
</p>

<?php } ?>

<div class="form-box">

<form method="POST" enctype="multipart/form-data">

<label>Product Name</label>

<input
type="text"
name="name"
required>

<label>Category</label>

<input
type="text"
name="category"
required>

<div class="row">

<div>

<label>Price (TZS)</label>

<input
type="number"
name="price"
min="1"
step="0.01"
required>

</div>

<div>

<label>Stock Quantity</label>

<input
type="number"
name="quantity"
min="0"
required>

</div>

</div>

<label>Product Image</label>

<input
type="file"
name="image"
accept=".jpg,.jpeg,.png,.webp"
required>

<label>Description</label>

<textarea
name="description"
required></textarea>

<button
type="submit"
name="addProduct">

<?php
echo $isAdmin
? "Add Product"
: "Submit Product";
?>

</button>

</form>

</div>

</div>

</body>
</html>