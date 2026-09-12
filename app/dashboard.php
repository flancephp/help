<?php

session_start();

if(!isset($_SESSION['logged_in'])){
    header("Location: login.php");
    exit();
}


$name = $_SESSION['name'];

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="Assets/css/style.css">
    <link rel="stylesheet" href="Assets/css/dashboard.css">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>        
    <link rel="preconnect" href="https://fonts.googleapis.com">

</head>
<body>

    <header>
        <div class="logo">
            <img src="Assets/images/logo2.png" alt="ExcellentIts Logo">
        </div>
        <div class="nav">

            <p>Welcome, <strong><?php echo $name; ?></strong>!</p>

            <a href="logout.php?logout=true">Logout</a>

        </div>
    </header>

    <img src="Assets/images/bg1.webp" alt="Background Image" class="bg">

    <h1>Welcome to the Dashboard</h1>

    <div class="dashboard">

        <a href="ManageEmployee/employees.php" class="card">Employees Details</a>
        <a href="Projects/ManageProjects/ManageProjects.php" class="card">Projects</a>
        <a href="#" class="card">View Reports</a>
        <a href="#" class="card">Client data</a>
        <a href="#" class="card">Testing</a>
    </div>

</body>
</html>