<?php

include (substr(__FILE__, 0, strrpos(__FILE__, '/') + 1) . "../inc/db.php");

session_start();

if(!isset($_SESSION['logged_in'])){
    header("Location: ../login.php");
    exit();
}

$name = $_SESSION['name'];

$insert = isset($_GET['success']);

if(isset($_POST['name'])){

    $name = trim($_POST['name']);
    $department = trim($_POST['department']);
    $salary = $_POST['salary'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    if (strlen($name) < 2 || strlen($name) > 15) {
        echo "Name must be between 2 and 15 characters.";
        exit();
    }
    
    if(empty($name)){
        echo "Name is required.";
        exit();
    }
    if(empty($department)){
        echo "Department is required.";
        exit();
    }
    if(empty($salary)){
        echo  "Salary is required.";
        exit();
    }
    if(empty($email)){
        echo "Email is required.";
        exit();
    }
    if(empty($phone)){
        echo "Phone is required.";
        exit();
    }else{

    $sql = "INSERT INTO `ExcellentIts`.`tbl_employee` (`name`, `department`, `salary`, `email`, `phone`, `dt`) VALUES ('$name', '$department', '$salary', '$email', '$phone', CURRENT_TIMESTAMP)";

    if ($con->query($sql) == true){

        $_SESSION['success'] = "Employee added successfully!";

        header("Location: ../ManageEmployee/employees.php?success=true");
        exit();
    }
    else{
        echo "Error: $sql <br> $con->error";    
    }

    $con->close();
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Employee</title>
    <link rel="stylesheet" href="../Assets/css/style.css">
    <link rel="stylesheet" href="../Assets/css/addEmployees.css">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>        
    <link rel="preconnect" href="https://fonts.googleapis.com">
</head>
<body>
    <header>
        <div class="logo">
            <img src="../Assets/images/logo2.png" alt="ExcellentIts Logo">
        </div>
        <div class="nav">

            <p>Welcome, <strong><?php echo $name; ?></strong>!</p>

            <a href="../logout.php?logout=true">Logout</a>

        </div>
    </header>

    <img src="../Assets/images/bg1.webp" alt="Background Image" class="bg">

    <div class="main">
        <button class="btn" onclick = "window.location.href='../ManageEmployee/employees.php'">Back</button>
        <h1>Add New Employee</h1>
        <button class="btn" onclick="document.forms[0].submit()">Save</button>
    </div>

    <div class="container">
        
            
            <form action="../ManageEmployee/add_employee.php" method="POST">
                <div>
                    <label for="name">Name:</label>
                    <input type="text" id="name" name="name" required placeholder="Employee Name">      
                </div>

                <div>
                    <label for="department">Department:</label>
                    <input type="text" id="department" name="department" required placeholder="Department">
                </div>

                <div>
                    <label for="salary">Salary:</label>
                    
                    <input type="number" id="salary" name="salary" required placeholder="Salary">
                </div>

                <div>
                    <label for="email">Email:</label>
                    
                    <input type="email" id="email" name="email" required placeholder="Email">
                </div>

                <div>
                    <label for="phone">Phone:</label>
                    
                    <input type="tel" id="phone" name="phone" required placeholder="Phone Number">
                </div>
            </form>
        </div>
    </div>

    <script src="../Assets/js/Common.js"></script>
    
</body>
</html>