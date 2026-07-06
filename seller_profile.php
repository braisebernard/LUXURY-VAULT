<?php

include("seller_auth.php");

$sellerId = $_SESSION['seller_id'];

/* ===============================
   LOAD CURRENT PROFILE
================================ */

$stmt = $conn->prepare("
SELECT *
FROM users
WHERE id=?
");

$stmt->bind_param("i",$sellerId);
$stmt->execute();

$user = $stmt->get_result()->fetch_assoc();

/* ===============================
   UPDATE PROFILE
================================ */

if(isset($_POST['saveProfile'])){

    $shopName = trim($_POST['shop_name']);
    $description = trim($_POST['shop_description']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);
    $facebook = trim($_POST['facebook']);
    $instagram = trim($_POST['instagram']);
    $website = trim($_POST['website']);

    $logo = $user['shop_logo'];
    $banner = $user['shop_banner'];

    /* LOGO */

    if(isset($_FILES['shop_logo']) &&
       $_FILES['shop_logo']['error']==0){

        $ext = strtolower(
        pathinfo(
        $_FILES['shop_logo']['name'],
        PATHINFO_EXTENSION));

        $allowed=[
        "jpg",
        "jpeg",
        "png",
        "webp"
        ];

        if(in_array($ext,$allowed)){

            $logo="logo_".uniqid().".".$ext;

            move_uploaded_file(

            $_FILES['shop_logo']['tmp_name'],

            "images/".$logo

            );

        }

    }

    /* BANNER */

    if(isset($_FILES['shop_banner']) &&
       $_FILES['shop_banner']['error']==0){

        $ext=strtolower(
        pathinfo(
        $_FILES['shop_banner']['name'],
        PATHINFO_EXTENSION));

        $allowed=[
        "jpg",
        "jpeg",
        "png",
        "webp"
        ];

        if(in_array($ext,$allowed)){

            $banner="banner_".uniqid().".".$ext;

            move_uploaded_file(

            $_FILES['shop_banner']['tmp_name'],

            "images/".$banner

            );

        }

    }

    $stmt=$conn->prepare("

    UPDATE users

    SET

    shop_name=?,

    shop_logo=?,

    shop_banner=?,

    shop_description=?,

    phone=?,

    address=?,

    facebook=?,

    instagram=?,

    website=?

    WHERE id=?

    ");

    $stmt->bind_param(

    "sssssssssi",

    $shopName,

    $logo,

    $banner,

    $description,

    $phone,

    $address,

    $facebook,

    $instagram,

    $website,

    $sellerId

    );

    if($stmt->execute()){

        header("Location:seller_profile.php?saved=1");
        exit();

    }

}
?>
<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
content="width=device-width, initial-scale=1.0">

<title>Seller Store Profile</title>

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

.success{
background:#14532d;
padding:15px;
border-radius:8px;
margin-bottom:25px;
border:1px solid #22c55e;
}

.profile-box{
background:#111;
padding:30px;
border-radius:15px;
border:1px solid #222;
max-width:1000px;
}

.preview{
display:flex;
gap:25px;
margin-bottom:30px;
align-items:center;
flex-wrap:wrap;
}

.banner{

width:100%;
max-width:650px;
height:220px;

border-radius:15px;
object-fit:cover;
border:2px solid gold;

}

.logo-preview{

width:150px;
height:150px;
border-radius:50%;
object-fit:cover;
border:4px solid gold;

}

.row{

display:grid;
grid-template-columns:1fr 1fr;
gap:20px;

}

label{

display:block;
margin-bottom:8px;
color:gold;
font-weight:bold;

}

input,
textarea{

width:100%;
padding:14px;
margin-bottom:20px;

background:#1a1a1a;

color:white;

border:1px solid #333;

border-radius:8px;

}

textarea{

height:140px;
resize:none;

}

button{

width:100%;
padding:16px;

background:gold;

color:black;

font-weight:bold;

font-size:18px;

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

<div class="logo">

LuxuryVault

</div>

<a href="seller_dashboard.php">Dashboard</a>

<a href="add_product.php">Add Product</a>

<a href="manage_products.php">My Products</a>

<a href="seller_orders.php">Orders</a>

<a href="seller_profile.php">Store Profile</a>

<a href="index.php">Marketplace</a>

<a href="logout.php">Logout</a>

</div>

<div class="main">

<h1 class="title">

Store Profile

</h1>

<?php if(isset($_GET['saved'])){ ?>

<div class="success">

✅ Your store profile has been updated successfully.

</div>

<?php } ?>

<div class="profile-box">

<div class="preview">

<div>

<p style="color:gold;margin-bottom:10px;font-weight:bold;">

Store Logo

</p>

<img

class="logo-preview"

src="<?php

if(!empty($user['shop_logo'])){

echo "images/".htmlspecialchars($user['shop_logo']);

}else{

echo "https://via.placeholder.com/150?text=Logo";

}

?>">

</div>

<div style="flex:1;">

<p style="color:gold;margin-bottom:10px;font-weight:bold;">

Store Banner

</p>

<img

class="banner"

src="<?php

if(!empty($user['shop_banner'])){

echo "images/".htmlspecialchars($user['shop_banner']);

}else{

echo "https://via.placeholder.com/900x250?text=Store+Banner";

}

?>">

</div>

</div>

<form method="POST" enctype="multipart/form-data">

<label>Store Name</label>

<input

type="text"

name="shop_name"

value="<?php echo htmlspecialchars($user['shop_name']); ?>"

required>

<div class="row">

<div>

<label>Upload Logo</label>

<input

type="file"

name="shop_logo"

accept=".jpg,.jpeg,.png,.webp">

</div>

<div>

<label>Upload Banner</label>

<input

type="file"

name="shop_banner"

accept=".jpg,.jpeg,.png,.webp">

</div>

</div>

<label>Store Description</label>

<textarea

name="shop_description"><?php echo htmlspecialchars($user['shop_description']); ?></textarea>

<div class="row">

<div>

<label>Phone Number</label>

<input

type="text"

name="phone"

value="<?php echo htmlspecialchars($user['phone']); ?>">

</div>

<div>

<label>Business Address</label>

<input

type="text"

name="address"

value="<?php echo htmlspecialchars($user['address']); ?>">

</div>

</div>

<div class="row">

<div>

<label>Facebook</label>

<input

type="text"

name="facebook"

placeholder="https://facebook.com/yourpage"

value="<?php echo htmlspecialchars($user['facebook']); ?>">

</div>

<div>

<label>Instagram</label>

<input

type="text"

name="instagram"

placeholder="https://instagram.com/yourpage"

value="<?php echo htmlspecialchars($user['instagram']); ?>">

</div>

</div>

<label>Website</label>

<input

type="text"

name="website"

placeholder="https://www.yourstore.com"

value="<?php echo htmlspecialchars($user['website']); ?>">

<button

type="submit"

name="saveProfile">

Save Store Profile

</button>

</form>

</div>

</div>

</body>

</html>
