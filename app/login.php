<?php

include (substr(__FILE__, 0, strrpos(__FILE__, '/') + 1) . "inc/db.php");

session_start();

if (isset($_POST['username'])) {

$username = $_POST['username'];
$password = $_POST['password'];

$sql = "SELECT * FROM `ExcellentIts`.`tbl_RegisterUser` WHERE `username`='$username' AND `password`='$password'";

$result = $con->query($sql);


if ($result->num_rows > 0){

    $row = $result->fetch_assoc();


    $_SESSION['logged_in'] = true;
    $_SESSION['name'] = $row['name'];
    $_SESSION['username'] = $row['username'];
    $_SESSION['user_id'] = $row['sno'];

    header("Location: dashboard.php");
    exit();
}
else{

    $_SESSION['error'] = "Invalid username or password";

    header("Location: login.php");
    exit();

}

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    
    <link rel="stylesheet" href="Assets/css/style.css">
    <link rel="stylesheet" href="Assets/css/login.css">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>        
    <link rel="preconnect" href="https://fonts.googleapis.com">
    
</head>
<body>


    <header>
        <div class="logo">
            <img src="Assets/images/logo2.png" alt="ExcellentIts Logo">
        </div>
        <div class="nav">
                <a href="login.php">Login</a>
                <a href="register.php">Register</a>
        </div>
    </header>

    <img src="Assets/images/bg1.webp" alt="Background Image" class="bg">

    <?php
            if (isset($_SESSION['success'])){
                echo "<p class='submitmsg' id='submitmsg'>" . $_SESSION['success'] . "</p>";
                unset($_SESSION['success']);
            }

            if (isset($_SESSION['error'])){
                echo "<p class='submitmsg' id='submitmsg'>" . $_SESSION['error'] . "</p>";
                unset($_SESSION['error']);
            }
            ?>

    <div class="main">
        <div class="container">

            <h1>Login to Your Account</h1>

            
            <form action="login.php" method="POST">
                <div>
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" required placeholder="Username">
                </div>

                <div>
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required placeholder="Password">
                </div>

                <button type="submit" class="btn">Login</button>

                <div class="register-link">
                    <p>Don't have an account? <a href="register.php">Register here</a></p>
                </div>
            </form>

        </div>
    </div>
    <script src="Assets/js/Common.js"></script>
</body>
</html>