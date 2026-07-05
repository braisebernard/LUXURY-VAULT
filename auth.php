<?php
session_set_cookie_params([
    'httponly' => true,
    'samesite' => 'Lax'
]);

session_start();
include("connect.php");

/* ==========================================
   MARKETPLACE REGISTRATION
========================================== */

if(isset($_POST['signUp'])){

    $firstName = trim($_POST['fName']);
    $lastName  = trim($_POST['lName']);
    $email     = strtolower(trim($_POST['email']));
    $password  = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];

    $role = $_POST['role'];

    $phone = trim($_POST['phone'] ?? '');
    $shopName = trim($_POST['shop_name'] ?? '');
    $shopAddress = trim($_POST['shop_address'] ?? '');
    $shopDescription = trim($_POST['shop_description'] ?? '');

    if($password != $confirmPassword){

        echo "<script>
        alert('Passwords do not match');
        window.location='register.php';
        </script>";

        exit();

    }

    if($role != "customer" && $role != "seller"){

        die("Invalid role selected.");

    }

    $stmt = $conn->prepare(
    "SELECT id FROM users WHERE email=?");

    $stmt->bind_param("s",$email);

    $stmt->execute();

    $result = $stmt->get_result();

    if($result->num_rows>0){

        echo "<script>
        alert('Email already exists');
        window.location='register.php';
        </script>";

        exit();

    }

    if($role=="seller"){

        if(
        empty($shopName) ||
        empty($phone) ||
        empty($shopAddress)
        ){

            echo "<script>

            alert('Complete all seller information.');

            window.location='register.php';

            </script>";

            exit();

        }

        $status="pending";

    }else{

        $status="approved";

        $shopName=NULL;
        $phone=NULL;
        $shopAddress=NULL;
        $shopDescription=NULL;

    }

    $hashedPassword =
    password_hash(
    $password,
    PASSWORD_DEFAULT
    );

    $stmt = $conn->prepare(

    "INSERT INTO users
    (
    firstName,
    lastName,
    email,
    password,
    role,
    status,
    phone,
    shop_name,
    shop_description,
    shop_address
    )

    VALUES

    (?,?,?,?,?,?,?,?,?,?)"

    );

    $stmt->bind_param(

    "ssssssssss",

    $firstName,
    $lastName,
    $email,
    $hashedPassword,
    $role,
    $status,
    $phone,
    $shopName,
    $shopDescription,
    $shopAddress

    );

    if($stmt->execute()){

        if($role=="seller"){

            echo "<script>

            alert('Seller account created successfully. Please wait for Admin approval.');

            window.location='login.php';

            </script>";

        }else{

            echo "<script>

            alert('Registration Successful');

            window.location='login.php';

            </script>";

        }

    }else{

        die("Database Error : ".$conn->error);

    }

}

/* ==========================================
   MARKETPLACE LOGIN
========================================== */

if(isset($_POST['signIn'])){

    $email = strtolower(trim($_POST['email']));
    $password = $_POST['password'];

    $stmt = $conn->prepare(
    "SELECT * FROM users WHERE email=?");

    $stmt->bind_param("s",$email);
    $stmt->execute();

    $result = $stmt->get_result();

    if($result->num_rows==1){

        $user = $result->fetch_assoc();

        if(password_verify($password,$user['password'])){

            // Seller waiting approval
            if(
                $user['role']=="seller" &&
                $user['status']=="pending"
            ){

                echo "<script>

                alert('Your seller account is waiting for administrator approval.');

                window.location='login.php';

                </script>";

                exit();

            }

            // Seller rejected
            if(
                $user['role']=="seller" &&
                $user['status']=="rejected"
            ){

                echo "<script>

                alert('Your seller application has been rejected.');

                window.location='login.php';

                </script>";

                exit();

            }

            session_regenerate_id(true);

            $_SESSION['user_id']=$user['id'];
            $_SESSION['email']=$user['email'];
            $_SESSION['role']=$user['role'];
            $_SESSION['firstName']=$user['firstName'];

            // Redirect by role

            if($user['role']=="admin"){

                header("Location:admin.php");
                exit();

            }

            if($user['role']=="seller"){

                header("Location:seller_dashboard.php");
                exit();

            }

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