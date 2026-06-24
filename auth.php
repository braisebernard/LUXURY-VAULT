<?php

session_start();
include("connect.php");


// ================= REGISTER =================

if(isset($_POST['signUp'])){

    $firstName = $_POST['fName'];
    $lastName  = $_POST['lName'];
    $email     = $_POST['email'];
    $password  = $_POST['password'];

    // CHECK EXISTING EMAIL
    $checkEmail = mysqli_query($conn,
    "SELECT * FROM users WHERE email='$email'");

   if(mysqli_num_rows($checkEmail) > 0){

    echo "
    <script>
        alert('Email already exists');
        window.location.href='register.php';
    </script>
    ";

    exit();
}

    // INSERT USER
    $insertQuery = "INSERT INTO users
    (firstName,lastName,email,password)

    VALUES
    ('$firstName','$lastName','$email','$password')";

    if(mysqli_query($conn,$insertQuery)){

        echo "
        <script>
            alert('Registration Successful');
            window.location.href='login.php';
        </script>
        ";

    }else{

        echo "
        <script>
            alert('Registration Failed');
            window.location.href='register.php';
        </script>
        ";
    }

}



// ================= LOGIN =================

if(isset($_POST['signIn'])){

    $email    = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users
    WHERE email='$email'
    AND password='$password'";

    $result = mysqli_query($conn,$sql);

    if(mysqli_num_rows($result) > 0){

        $_SESSION['email'] = $email;

        header("Location:index.php");
        exit();

    }else{

        echo "
        <script>
            alert('Incorrect Email or Password');
            window.location.href='login.php';
        </script>
        ";
    }

}

?>