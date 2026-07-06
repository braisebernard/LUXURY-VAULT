<?php

include("product_auth.php");

if(!isset($_GET['id']) || !is_numeric($_GET['id'])){
    die("Invalid Product.");
}

$productId = (int)$_GET['id'];

/* =========================================
   LOAD PRODUCT
========================================= */

if($isAdmin){

    $stmt = $conn->prepare("
    SELECT *
    FROM products
    WHERE id=?
    ");

    $stmt->bind_param("i",$productId);

}else{

    $sellerId = $_SESSION['user_id'];

    $stmt = $conn->prepare("
    SELECT *
    FROM products
    WHERE id=?
    AND seller_id=?
    ");

    $stmt->bind_param(
        "ii",
        $productId,
        $sellerId
    );

}

$stmt->execute();

$result = $stmt->get_result();

if($result->num_rows==0){

    die("Access Denied.");

}

$product = $result->fetch_assoc();

/* =========================================
   UPDATE PRODUCT
========================================= */

if(isset($_POST['updateProduct'])){

    $name = trim($_POST['name']);
    $category = trim($_POST['category']);
    $price = (float)$_POST['price'];
    $quantity = (int)$_POST['quantity'];
    $description = trim($_POST['description']);

    $image = $product['image'];

    /* Image Upload */

    if(isset($_FILES['image']) &&
       $_FILES['image']['error']==0){

        $allowed = [
            "jpg",
            "jpeg",
            "png",
            "webp"
        ];

        $extension = strtolower(
            pathinfo(
                $_FILES['image']['name'],
                PATHINFO_EXTENSION
            )
        );

        if(in_array($extension,$allowed)){

            $image =
            uniqid("product_").".".$extension;

            move_uploaded_file(

                $_FILES['image']['tmp_name'],

                "images/".$image

            );

        }

    }

    if($isAdmin){

        $status = $_POST['status'];

        $stmt = $conn->prepare("

        UPDATE products

        SET

        name=?,

        category=?,

        price=?,

        quantity=?,

        description=?,

        image=?,

        status=?

        WHERE id=?

        ");

        $stmt->bind_param(

        "ssdisssi",

        $name,

        $category,

        $price,

        $quantity,

        $description,

        $image,

        $status,

        $productId

        );

    }else{

        $stmt = $conn->prepare("

        UPDATE products

        SET

        name=?,

        category=?,

        price=?,

        quantity=?,

        description=?,

        image=?

        WHERE id=?

        ");

        $stmt->bind_param(

        "ssdissi",

        $name,

        $category,

        $price,

        $quantity,

        $description,

        $image,

        $productId

        );

    }

    if($stmt->execute()){

        header("Location: manage_products.php");
        exit();

    }else{

        die($conn->error);

    }

}

?>
<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
content="width=device-width, initial-scale=1.0">

<title>Edit Product</title>

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

.form-box{
max-width:900px;
background:#111;
padding:35px;
border-radius:15px;
border:1px solid #222;
}

label{
display:block;
margin-bottom:8px;
color:gold;
}

input,
textarea,
select{

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

.row{

display:grid;

grid-template-columns:1fr 1fr;

gap:20px;

}

.preview{

width:220px;

height:220px;

object-fit:cover;

border-radius:12px;

border:2px solid gold;

margin-bottom:25px;

}

button{

width:100%;

padding:16px;

background:gold;

border:none;

color:black;

font-size:18px;

font-weight:bold;

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

<h1 class="title">

Edit Product

</h1>

<div class="form-box">

<form method="POST" enctype="multipart/form-data">

<label>Current Image</label>

<img
class="preview"
src="images/<?php echo htmlspecialchars($product['image']); ?>">

<label>Change Image</label>

<input
type="file"
name="image"
accept=".jpg,.jpeg,.png,.webp">

<label>Product Name</label>

<input
type="text"
name="name"
value="<?php echo htmlspecialchars($product['name']); ?>"
required>

<label>Category</label>

<input
type="text"
name="category"
value="<?php echo htmlspecialchars($product['category']); ?>"
required>

<div class="row">

<div>

<label>Price</label>

<input
type="number"
step="0.01"
min="1"
name="price"
value="<?php echo $product['price']; ?>"
required>

</div>

<div>

<label>Stock Quantity</label>

<input
type="number"
min="0"
name="quantity"
value="<?php echo $product['quantity']; ?>"
required>

</div>

</div>

<label>Description</label>

<textarea
name="description"
required><?php echo htmlspecialchars($product['description']); ?></textarea>

<?php if($isAdmin){ ?>

<label>Product Status</label>

<select name="status">

<option
value="approved"
<?php if($product['status']=="approved") echo "selected"; ?>>
Approved
</option>

<option
value="pending"
<?php if($product['status']=="pending") echo "selected"; ?>>
Pending
</option>

<option
value="rejected"
<?php if($product['status']=="rejected") echo "selected"; ?>>
Rejected
</option>

</select>

<?php } ?>

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
