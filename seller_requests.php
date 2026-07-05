<?php

include("connect.php");
include("admin_auth.php");

$query = mysqli_query($conn,"
SELECT *
FROM users
WHERE role='seller'
ORDER BY
CASE
WHEN status='pending' THEN 1
WHEN status='approved' THEN 2
WHEN status='rejected' THEN 3
END,
id DESC
");

?>

<!DOCTYPE html>
<html>

<head>

<title>Seller Applications</title>

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

.navbar{
background:black;
padding:20px 50px;
display:flex;
justify-content:space-between;
align-items:center;
border-bottom:1px solid #222;
}

.logo{
font-size:28px;
font-weight:bold;
color:gold;
}

.navbar a{
color:white;
text-decoration:none;
margin-left:20px;
}

.navbar a:hover{
color:gold;
}

.container{
width:90%;
margin:40px auto;
}

h1{
text-align:center;
color:gold;
margin-bottom:40px;
}

.card{
background:#111;
border:1px solid #222;
padding:25px;
border-radius:15px;
margin-bottom:25px;
}

.card p{
margin:10px 0;
line-height:1.8;
}

.status{
font-weight:bold;
}

.pending{
color:orange;
}

.approved{
color:#32CD32;
}

.rejected{
color:red;
}

.actions{
margin-top:20px;
}

.btn{
padding:12px 22px;
border:none;
border-radius:8px;
cursor:pointer;
font-weight:bold;
text-decoration:none;
display:inline-block;
margin-right:10px;
}

.approve{
background:#32CD32;
color:white;
}

.reject{
background:#d32f2f;
color:white;
}

.back{
background:gold;
color:black;
}

</style>

</head>

<body>

<div class="navbar">

<div class="logo">

LuxuryVault

</div>

<div>

<a href="admin.php">

Dashboard

</a>

<a href="logout.php">

Logout

</a>

</div>

</div>

<div class="container">

<h1>

Seller Applications

</h1>

<?php

if(mysqli_num_rows($query)==0){

echo "<h2 style='text-align:center;color:#999;'>
No seller applications found.
</h2>";

}

while($seller=mysqli_fetch_assoc($query)){

?>

<div class="card">

<h2 style="color:gold;">

<?php echo htmlspecialchars($seller['shop_name']); ?>

</h2>

<p>

<strong>Owner:</strong>

<?php
echo htmlspecialchars(
$seller['firstName']." ".$seller['lastName']
);
?>

</p>

<p>

<strong>Email:</strong>

<?php echo htmlspecialchars($seller['email']); ?>

</p>

<p>

<strong>Phone:</strong>

<?php echo htmlspecialchars($seller['phone']); ?>

</p>

<p>

<strong>Business Address:</strong>

<?php echo htmlspecialchars($seller['shop_address']); ?>

</p>

<p>

<strong>Description:</strong>

<?php echo htmlspecialchars($seller['shop_description']); ?>

</p>

<p class="status">

Status :

<span class="<?php echo strtolower($seller['status']); ?>">

<?php echo ucfirst($seller['status']); ?>

</span>

</p>

<div class="actions">

<?php

if($seller['status']=="pending"){

?>

<a
class="btn approve"
href="update_seller.php?id=<?php echo $seller['id']; ?>&status=approved">

Approve

</a>

<a
class="btn reject"
href="update_seller.php?id=<?php echo $seller['id']; ?>&status=rejected">

Reject

</a>

<?php

}else{

echo "<b>No action required.</b>";

}

?>

</div>

</div>

<?php

}

?>

<a
class="btn back"
href="admin.php">

← Back To Dashboard

</a>

</div>

</body>

</html>