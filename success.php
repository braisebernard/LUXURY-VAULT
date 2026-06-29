<?php
session_set_cookie_params([
    'httponly' => true,
    'samesite' => 'Lax'
]);

session_start();
?>

<!DOCTYPE html>
<html>
<head>
<title>Order Successful</title>

<style>
body{
    background:#0b0b0b;
    color:white;
    text-align:center;
    font-family:Poppins,sans-serif;
    padding-top:100px;
}

h1{
    color:gold;
}
</style>

</head>
<body>

<h1>Order Placed Successfully!</h1>

<p>Thank you for shopping with LuxuryVault.</p>

<a href="index.php">
    <button style="
    background:gold;
    padding:15px 25px;
    border:none;
    cursor:pointer;">
        Return Home
    </button>
</a>

</body>
</html>