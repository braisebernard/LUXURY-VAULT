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
</head>

<body>

<div class="container" id="signup">

    <h1 class="form-title">Register</h1>

    <form method="post" action="auth.php">

        <div class="input-group">
            <i class="fas fa-user"></i>

            <input type="text"
            name="fName"
            placeholder="First Name"
            required>

            <label>First Name</label>
        </div>

        <div class="input-group">
            <i class="fas fa-user"></i>

            <input type="text"
            name="lName"
            placeholder="Last Name"
            required>

            <label>Last Name</label>
        </div>

        <div class="input-group">
            <i class="fas fa-envelope"></i>

            <input type="email"
            name="email"
            placeholder="Email"
            required>

            <label>Email</label>
        </div>

        <div class="input-group">
            <i class="fas fa-lock"></i>

            <input type="password"
            name="password"
            placeholder="Password"
            required>

            <label>Password</label>
        </div>

        <input type="submit"
        class="btn"
        value="Register"
        name="signUp">

    </form>

    <div class="links">
        <p>Already have account?</p>

        <a href="login.php">
            <button>Sign In</button>
        </a>
    </div>

</div>

</body>
</html>