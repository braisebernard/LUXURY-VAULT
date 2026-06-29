<?php
session_set_cookie_params([
    'httponly' => true,
    'samesite' => 'Lax'
]);

session_start();
include("connect.php");

if(isset($_POST['send'])){

$name = $_POST['name'];
$email = $_POST['email'];
$message = $_POST['message'];

mysqli_query($conn,

"INSERT INTO contacts
(name,email,message)

VALUES

('$name','$email','$message')");

echo "<script>alert('Message Sent Successfully');</script>";
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Contact Us</title>

<style>

body{
background:#0b0b0b;
color:white;
font-family:Poppins,sans-serif;
}

.container{
width:500px;
margin:50px auto;
background:#111;
padding:30px;
border-radius:10px;
}

input,textarea{
width:100%;
padding:12px;
margin:10px 0;
background:#222;
color:white;
border:none;
}

button{
padding:12px 20px;
background:gold;
border:none;
font-weight:bold;
cursor:pointer;
}

h1{
text-align:center;
color:gold;
}

</style>
</head>

<body>

<div class="container">

<h1>Contact Us</h1>

<form method="POST">

<input
type="text"
name="name"
placeholder="Your Name"
required>

<input
type="email"
name="email"
placeholder="Your Email"
required>

<textarea
name="message"
placeholder="Your Message"
rows="6"
required></textarea>

<button
type="submit"
name="send">

Send Message

</button>

</form>

</div>

</body>
</html>