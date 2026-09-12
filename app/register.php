<?php

include (substr(__FILE__, 0, strrpos(__FILE__, '/') + 1) . "inc/db.php");

session_start();

$insert = isset($_GET['success']);

if(isset($_POST['name'])){

    $name = $_POST['name'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    // $sql = "INSERT INTO `ExcellentIts`.`tbl_RegisterUser` (`name`, `username`, `password`, `email`, `phone`, `dt`) VALUES ('$name', '$username', '$password', '$email', '$phone', CURRENT_TIMESTAMP)";

    $sql = "INSERT INTO `tbl_RegisterUser` (`name`='$name', `username`='$username', `password`='$password', `email`='$email', `phone`='$phone', `dt`=CURRENT_TIMESTAMP)";

    if ($con->query($sql) == true){

        $_SESSION['success'] = "User registered successfully!";

        header("Location: login.php?success=true");
        exit();
    }
    else{
        echo "Error: $sql <br> $con->error";    
    }

    $con->close();

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration</title>
    <link rel="stylesheet" href="Assets/css/style.css">
    <link rel="stylesheet" href="Assets/css/register.css">
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

    <div class="main">
        <div class="heading">
            <h1>Excellent</h1>
            <h2>IT Solutions & Services.</h2>
            <p>We serve the best IT services in the market.</p>
        </div>
        <div class="container">

            <h1>Fill the Registration Form</h1>

            <!-- <?php
            if (isset($_SESSION['success'])){
                echo "<p class='submitmsg' id='submitmsg'>" . $_SESSION['success'] . "</p>";
                unset($_SESSION['success']);
            }
            ?> -->
            <form action="register.php" method="POST">
                <div>
                    <label for="name">Name:</label>
                    <input type="text" id="name" name="name" required placeholder="Your Name">
                </div>

                <div>
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" required placeholder="Username">
                </div>

                <div>
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required placeholder="Password">
                </div>

                <div>
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required placeholder="Email">
                </div>

                <div>
                    <label for="phone">Phone:</label>
                    <input type="tel" id="phone" name="phone" required placeholder="Phone Number">
                </div>

                <button type="submit" class="btn">Register</button>

            </form>
        </div>
    </div>

    <script src="Assets/js/Common.js"></script>
    
</body>
</html>