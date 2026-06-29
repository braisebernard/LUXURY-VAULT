<?php
include("connect.php");
include("admin_auth.php");

$query = mysqli_query($conn,
"SELECT * FROM users");
?>

<!DOCTYPE html>
<html>
<head>
<title>Users</title>

<style>

body{
background:#0b0b0b;
color:white;
font-family:Poppins,sans-serif;
}

table{
width:90%;
margin:auto;
border-collapse:collapse;
}

th{
background:gold;
color:black;
padding:15px;
}

td{
padding:15px;
border:1px solid #222;
}

h1{
text-align:center;
color:gold;
margin:30px;
}

</style>

</head>
<body>

<h1>Registered Users</h1>

<table>

<tr>
<th>ID</th>
<th>First Name</th>
<th>Last Name</th>
<th>Email</th>
<th>Admin</th>
</tr>

<?php while($row=mysqli_fetch_assoc($query)){ ?>

<tr>

<td><?php echo $row['id']; ?></td>

<td><?php echo $row['firstName']; ?></td>

<td><?php echo $row['lastName']; ?></td>

<td><?php echo $row['email']; ?></td>

<td>
<?php
echo ($row['is_admin']==1)
? "Admin"
: "User";
?>
</td>

</tr>

<?php } ?>

</table>

</body>
</html>