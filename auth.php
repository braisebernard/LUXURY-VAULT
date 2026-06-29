<?php
session_set_cookie_params([
    'httponly' => true,
    'samesite' => 'Lax'
]);

session_start();
include("connect.php");

/* ==========================================
   USER REGISTRATION
========================================== */

if(isset($_POST['signUp'])){

    $firstName = trim($_POST['fName']);
    $lastName  = trim($_POST['lName']);
    $email     = strtolower(trim($_POST['email']));
    $password  = $_POST['password'];

    // Check if email already exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE email=?");
    $stmt->bind_param("s",$email);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows > 0){

        echo "<script>
        alert('Email already exists');
        window.location='login.php';
        </script>";
        exit();

    }

    // Hash password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insert user
    $stmt = $conn->prepare(
    "INSERT INTO users(firstName,lastName,email,password)
    VALUES(?,?,?,?)");

    $stmt->bind_param(
    "ssss",
    $firstName,
    $lastName,
    $email,
    $hashedPassword
    );

    if($stmt->execute()){

        echo "<script>
        alert('Registration Successful');
        window.location='login.php';
        </script>";

    }else{

        die("Database Error: ".$conn->error);

    }

}


/* ==========================================
   USER LOGIN
========================================== */

if(isset($_POST['signIn'])){

    $email = strtolower(trim($_POST['email']));
    $password = $_POST['password'];

    $stmt = $conn->prepare(
    "SELECT * FROM users WHERE email=?");

    $stmt->bind_param("s",$email);
    $stmt->execute();

    $result = $stmt->get_result();

    if($result->num_rows == 1){

        $user = $result->fetch_assoc();

        if(password_verify($password,$user['password'])){

            session_regenerate_id(true);

            $_SESSION['email'] = $user['email'];

            header("Location:index.php");
            exit();

        }else{

            echo "<script>
            alert('Incorrect Password');
            window.location='login.php';
            </script>";

        }

    }else{

        echo "<script>
        alert('Email Not Found');
        window.location='login.php';
        </script>";

    }

}

?>