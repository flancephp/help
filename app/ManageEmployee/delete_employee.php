<?php

include (substr(__FILE__, 0, strrpos(__FILE__, '/') + 1) . "../inc/db.php");

session_start();

if(!isset($_SESSION['logged_in'])){
    header("Location: ../login.php");
    exit();
}

$name = $_SESSION['name'];

$id = $_GET['id'];

$sql = "DELETE FROM `ExcellentIts`.`tbl_employee` WHERE `sno` = $id";

    if ($con->query($sql) == true){

        $_SESSION['success'] = "Employee deleted successfully!";

        header("Location: ../ManageEmployee/employees.php?success=true");
        exit();
    }
    else{

        
        echo "Error deleting employee: " . $con->error;    
    }

    $con->close();

?>