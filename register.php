<!DOCTYPE html>
<html lang="en">
<head>

<meta charset="UTF-8">

<meta name="viewport"
content="width=device-width, initial-scale=1.0">

<title>LuxuryVault Register</title>

<link rel="stylesheet" href="style.css">

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<style>

.sellerFields{
display:none;
}

</style>

</head>

<body>

<div class="container" id="signup">

<h1 class="form-title">

Create Account

</h1>

<form method="post" action="auth.php">

<div class="input-group">

<i class="fas fa-user"></i>

<input
type="text"
name="fName"
placeholder="First Name"
required>

<label>First Name</label>

</div>

<div class="input-group">

<i class="fas fa-user"></i>

<input
type="text"
name="lName"
placeholder="Last Name"
required>

<label>Last Name</label>

</div>

<div class="input-group">

<i class="fas fa-envelope"></i>

<input
type="email"
name="email"
placeholder="Email"
required>

<label>Email</label>

</div>

<div class="input-group">

<i class="fas fa-lock"></i>

<input
type="password"
name="password"
placeholder="Password"
required>

<label>Password</label>

</div>

<div class="input-group">

<i class="fas fa-lock"></i>

<input
type="password"
name="confirmPassword"
placeholder="Confirm Password"
required>

<label>Confirm Password</label>

</div>

<h3 style="
color:gold;
margin:20px 0 25px 0;
font-size:24px;
font-weight:bold;
">

Choose Account Type

</h3>

<style>

.role-container{

display:flex;
gap:15px;
margin-bottom:25px;

}

.role-card{

flex:1;
background:#181818;
border:2px solid #333;
border-radius:12px;
padding:20px;
cursor:pointer;
transition:.3s;
text-align:center;

}

.role-card:hover{

border-color:gold;
transform:translateY(-3px);

}

.role-card.active{

border-color:gold;
background:#222;

}

.role-card i{

font-size:35px;
color:gold;
margin-bottom:12px;

}

.role-card h3{

margin-bottom:8px;
color:white;

}

.role-card p{

font-size:14px;
color:#999;

}

.role-card input{

display:none;

}

</style>

<div class="role-container">

<label class="role-card active" id="customerCard">

<input
type="radio"
name="role"
value="customer"
checked>

<i class="fas fa-user"></i>

<h3>

Customer

</h3>

<p>

Buy luxury products

</p>

</label>

<label class="role-card" id="sellerCard">

<input
type="radio"
name="role"
value="seller">

<i class="fas fa-store"></i>

<h3>

Seller

</h3>

<p>

Sell your luxury products

</p>

</label>

</div>

<div
id="sellerFields"
class="sellerFields">

<div class="input-group">

<i class="fas fa-store"></i>

<input
type="text"
name="shop_name"
placeholder="Shop Name">

<label>Shop Name</label>

</div>

<div class="input-group">

<i class="fas fa-phone"></i>

<input
type="text"
name="phone"
placeholder="Phone Number">

<label>Phone Number</label>

</div>

<div class="input-group">

<i class="fas fa-location-dot"></i>

<input
type="text"
name="shop_address"
placeholder="Business Address">

<label>Business Address</label>

</div>

<div class="input-group">

<i class="fas fa-file"></i>

<textarea
name="shop_description"
placeholder="Business Description"
style="
width:100%;
padding:15px;
background:#1a1a1a;
color:white;
border:1px solid #444;
border-radius:5px;
resize:none;
height:100px;"></textarea>

</div>

</div>

<input
type="submit"
class="btn"
value="Create Account"
name="signUp">

</form>

<div class="links">

<p>

Already have an account?

</p>

<a href="login.php">

<button>

Sign In

</button>

</a>

</div>

</div>

<script>

const customerCard=document.getElementById("customerCard");
const sellerCard=document.getElementById("sellerCard");

const sellerFields=document.getElementById("sellerFields");

customerCard.onclick=function(){

customerCard.classList.add("active");
sellerCard.classList.remove("active");

customerCard.querySelector("input").checked=true;

sellerFields.style.display="none";

}

sellerCard.onclick=function(){

sellerCard.classList.add("active");
customerCard.classList.remove("active");

sellerCard.querySelector("input").checked=true;

sellerFields.style.display="block";

}

window.onload=function(){

sellerFields.style.display="none";

}

</script>

</body>
</html>