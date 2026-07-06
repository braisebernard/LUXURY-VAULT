<?php

session_set_cookie_params([
    'httponly' => true,
    'samesite' => 'Lax'
]);

session_start();
include("config/connect.php");

$success = "";

if(isset($_POST['send'])){

    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $message = trim($_POST['message']);

    // Validation
    if(
        empty($name) ||
        empty($email) ||
        empty($message)
    ){
        die("All fields are required.");
    }

    if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        die("Invalid email address.");
    }

    if(strlen($name) > 100){
        die("Name is too long.");
    }

    if(strlen($message) > 1000){
        die("Message is too long.");
    }

    // Prepared Statement
    $stmt = $conn->prepare(
        "INSERT INTO contacts(name,email,message)
         VALUES(?,?,?)"
    );

    $stmt->bind_param(
        "sss",
        $name,
        $email,
        $message
    );

    if($stmt->execute()){

        $success = "Your message has been sent successfully.";

    }else{

        die("Database Error: ".$conn->error);

    }

}

?>

<!DOCTYPE html>
<html>

<head>

<title>LuxuryVault Contact</title>

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

.container{
width:90%;
max-width:600px;
margin:60px auto;
background:#111;
padding:40px;
border-radius:15px;
border:1px solid #222;
}

h1{
text-align:center;
color:gold;
margin-bottom:30px;
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
font-size:15px;
}

textarea{
resize:none;
height:180px;
}

button{
width:100%;
padding:15px;
background:gold;
color:black;
border:none;
border-radius:8px;
font-size:16px;
font-weight:bold;
cursor:pointer;
}

button:hover{
background:#d4af37;
}

.success{
background:#0f5132;
padding:15px;
border-radius:8px;
margin-bottom:25px;
text-align:center;
color:#d1ffd8;
}

.homeBtn{
margin-top:20px;
background:#333;
color:white;
}

.homeBtn:hover{
background:#555;
}

</style>

</head>

<body>

<div class="container">

<h1>Contact Us</h1>

<?php if($success!=""){ ?>

<div class="success">

<?php echo htmlspecialchars($success); ?>

</div>

<?php } ?>

<form method="POST">

<input
type="text"
name="name"
placeholder="Your Name"
maxlength="100"
required>

<input
type="email"
name="email"
placeholder="Your Email"
maxlength="150"
required>

<textarea
name="message"
placeholder="Write your message..."
maxlength="1000"
required></textarea>

<button
type="submit"
name="send">

Send Message

</button>

</form>

<a href="index.php">

<button class="homeBtn">

Go Back To Homepage

</button>

</a>

</div>

</body>

</html>