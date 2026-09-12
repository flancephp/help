<?php

include (substr(__FILE__, 0, strrpos(__FILE__, '/') + 1) . "../inc/db.php");

session_start();

if(!isset($_SESSION['logged_in'])){
    header("Location: ../login.php");
    exit();
}
// Array, variable,sql(cud) query, isset
// isset is used to check if a variable is set and is not NULL. It returns true if the variable exists and is not NULL, otherwise it returns false. the varilabe is exists or not.
// Post - Post request is used to send data to server,is not showing data in URL.
// Get - Get request is used to retrieve data from server, showing data in URL.
// Session start - we work here in session our data is not lost when we move to another page.
// $_SESSION - WE can store data in session and access it on any page of the website. It is used to maintain state across multiple pages.
// SESSEION UNSET - It is used to remove a variable from the session. It does not destroy the entire session, only the specified variable is removed.

// $name = 'ram';

// $id = $_GET['id'];

// $emp = ['name' => 'John', 'department' => '', 'address' => ['Delhi', 'mum'=>'Mumbai'], 'email' => '', 'phone' => ''];

// $emp['address'][0] = 'Delhi';

if(isset($_POST['name'])){

    $name = $_POST['name'];
    $department = $_POST['department'];
    $salary = $_POST['salary'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];


    $sql = "UPDATE `ExcellentIts`.`tbl_employee` SET `name`='$name', `department`='$department', `salary`='$salary', `email`='$email', `phone`='$phone' WHERE `sno` = $id";
    


    if ($con->query($sql) == true){

        $_SESSION['success'] = "Employee updated successfully!";

        header("Location: ../ManageEmployee/employees.php?success=true");
        exit();
    }

}

// get old employee data

$sql = "SELECT * FROM `ExcellentIts`.`tbl_employee` WHERE `sno` = $id";
$result = $con->query($sql);
$employee = $result->fetch_assoc();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Employee</title>
    <link rel="stylesheet" href="../Assets/css/style.css">
    <link rel="stylesheet" href="../Assets/css/editEmployee.css">
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
        <h1>Edit Employee</h1>
        <button class="btn" onclick="document.forms[0].submit()">Save</button>
    </div>

    <div class="container">
        
           
            <form action="../ManageEmployee/edit_employee.php?id=<?php echo $id; ?>" method="POST">
                <div>
                    <label for="name">Name:</label>
                    <input type="text" id="name" name="name" required placeholder="Employee Name" value="<?php echo $employee['name']; ?>">
                </div>

                <div>
                    <label for="department">Department:</label>
                    <input type="text" id="department" name="department" required placeholder="Department" value="<?php echo $employee['department']; ?>    ">
                </div>

                <div>
                    <label for="salary">Salary:</label>
                    <input type="number" id="salary" name="salary" required placeholder="Salary" value="<?php echo $employee['salary']; ?>">
                </div>

                <div>
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required placeholder="Email" value="<?php echo $employee['email']; ?>">
                </div>

                <div>
                    <label for="phone">Phone:</label>
                    <input type="tel" id="phone" name="phone" required placeholder="Phone Number" value="<?php echo $employee['phone']; ?>">
                </div>
            </form>
        </div>
    </div>

    <script src="../Assets/js/Common.js"></script>
    
</body>
</html>