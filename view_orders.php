<?php
session_start();
include("connect.php");

// ADMIN PROTECTION
if(!isset($_SESSION['email'])){
    header("Location: login.php");
    exit();
}

// FETCH ALL ORDERS
$query = mysqli_query($conn,
"SELECT * FROM orders ORDER BY order_date DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>View Orders | LuxuryVault</title>

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

/* NAVBAR */
.navbar{
    background:black;
    padding:20px 50px;
    display:flex;
    justify-content:space-between;
    align-items:center;
    border-bottom:1px solid #222;
}

.logo{
    color:gold;
    font-size:28px;
    font-weight:bold;
}

.nav-links a{
    color:white;
    text-decoration:none;
    margin-left:20px;
}

.nav-links a:hover{
    color:gold;
}

/* PAGE TITLE */
.title{
    text-align:center;
    margin:40px 0;
}

.title h1{
    color:gold;
    font-size:40px;
}

/* ORDER CARD */
.container{
    width:90%;
    margin:auto;
}

.order-card{
    background:#111;
    border:1px solid #222;
    border-radius:12px;
    padding:25px;
    margin-bottom:25px;
}

.order-card h2{
    color:gold;
    margin-bottom:15px;
}

.order-card p{
    margin:10px 0;
    color:#ddd;
}

.status{
    color:gold;
    font-weight:bold;
}

/* FORM */
form{
    margin-top:20px;
}

select{
    padding:10px;
    background:#1a1a1a;
    color:white;
    border:1px solid #333;
    border-radius:5px;
}

button{
    padding:10px 20px;
    border:none;
    background:gold;
    color:black;
    font-weight:bold;
    cursor:pointer;
    border-radius:5px;
    margin-left:10px;
}

button:hover{
    background:#d4af37;
}

.back{
    text-align:center;
    margin:40px;
}

.back a{
    color:gold;
    text-decoration:none;
}

</style>

</head>
<body>

<!-- NAVBAR -->
<div class="navbar">

    <div class="logo">LuxuryVault</div>

    <div class="nav-links">
        <a href="admin.php">Dashboard</a>
        <a href="logout.php">Logout</a>
    </div>

</div>

<!-- TITLE -->
<div class="title">
    <h1>Customer Orders</h1>
</div>

<div class="container">

<?php while($row = mysqli_fetch_assoc($query)){ ?>

<div class="order-card">

    <h2>Order #<?php echo $row['id']; ?></h2>

    <p><strong>Customer:</strong>
    <?php echo $row['customer_name']; ?></p>

    <p><strong>Email:</strong>
    <?php echo $row['user_email']; ?></p>

    <p><strong>Phone:</strong>
    <?php echo $row['phone']; ?></p>

    <p><strong>Address:</strong>
    <?php echo $row['address']; ?></p>

    <p><strong>Payment:</strong>
    <?php echo $row['payment_method']; ?></p>

    <p><strong>Total:</strong>
    TZS <?php echo number_format($row['total']); ?></p>

    <p class="status">
        Current Status:
        <?php echo $row['status']; ?>
    </p>

    <form action="update_status.php" method="POST">

        <input type="hidden"
        name="id"
        value="<?php echo $row['id']; ?>">

        <select name="status">

            <option value="Pending">Pending</option>

            <option value="Processing">Processing</option>

            <option value="Delivered">Delivered</option>

            <option value="Cancelled">Cancelled</option>

        </select>

        <button type="submit">
            Update Status
        </button>

    </form>

</div>

<?php } ?>

</div>

<div class="back">
    <a href="admin.php">
        ← Back to Dashboard
    </a>
</div>

</body>
</html>