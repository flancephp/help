<?php

include (substr(__FILE__, 0, strrpos(__FILE__, '/') + 1) . "../inc/db.php");

session_start();

$sql = "SELECT * FROM `ExcellentIts`.`tbl_employee` ORDER BY `sno` ";
$result = $con->query($sql);


if(!isset($_SESSION['logged_in'])){
    header("Location: ../login.php");
    exit();
}

$name = $_SESSION['name'];



?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ManageEmployees</title>
    <link rel="stylesheet" href="../Assets/css/style.css">
    <link rel="stylesheet" href="../Assets/css/employees.css">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>        
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" integrity="sha512-..." crossorigin="anonymous" referrerpolicy="no-referrer" />

</head>
<body>


    <header>
        <div class="logo">
            <img src="../Assets/images/logo2.png" alt="ExcellentIts Logo">
        </div>
        <div class="nav">

            <p>Welcome,<strong><?php echo $name; ?></strong>!</p>

            <a href="../logout.php?logout=true">Logout</a>

        </div>
    </header>

    <img src="../Assets/images/bg1.webp" alt="Background Image" class="bg">

    <?php

        if (isset($_SESSION['success'])){
            echo "<p class='submitmsg' id='submitmsg'>" . $_SESSION['success'] . "</p>";
            unset($_SESSION['success']);
        }
    ?>
    <div class="container">
        <button class="btn" onclick = "window.location.href='../dashboard.php'">Back</button>
        <h1>Manage Employees</h1>
        <button class="btn" onclick = "window.location.href = '../ManageEmployee/add_employee.php'">Add Employee</button>
    </div>

    


    <div class="employee-table">
        <table>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Department</th>
                <th>salary</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Actions</th>
                
            </tr>

            <?php
            if ($result->num_rows > 0){

                $srNo = 1;

                while($row = $result->fetch_assoc()){
                    echo "<tr>";
                    echo "<td>" . $srNo . "</td>";
                    echo "<td>" . $row['name'] . "</td>";
                    echo "<td>" . $row['department'] . "</td>";
                    echo "<td>" . $row['salary'] . "</td>";
                    echo "<td>" . $row['email'] . "</td>";
                    echo "<td>" . $row['phone'] . "</td>";
                    echo "<td>
                        <a href='../ManageEmployee/edit_employee.php?id=" . $row['sno'] . "' title='Edit'>
                            <i class='fa-regular fa-pen-to-square'></i>
                        </a> | 
                        <a href='../ManageEmployee/delete_employee.php?id=" . $row['sno'] . "' onclick=\"return confirm('Are you sure you want to delete this employee?');\"><i class=\"fa-regular fa-trash-can\"></i></a></td>";
                    echo "</tr>";
                    $srNo++;
                }
            }
            else{
                echo "<tr><td colspan='7'>No employees found.</td></tr>";
            }
            ?>
        </table>
    </div>   
        
    <script src="../Assets/js/Common.js"></script>

</body>
</html>
