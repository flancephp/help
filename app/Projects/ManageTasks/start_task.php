<?php

include (substr(__FILE__, 0, strrpos(__FILE__, '/') + 1) . "../../inc/db.php");

session_start();

if (!isset($_SESSION['logged_in'])) {
    header("Location: ../../login.php");
    exit();
}


if (!isset($_GET['work_id'])) {
    die("Work ID not found.");
}


$work_id = (int)$_GET['work_id'];

$task_id = (int)$_GET['task_id'];

$project_id = (int)$_GET['project_id'];

$module_id = (int)$_GET['module_id'];



$sql = "UPDATE tbl_task_work SET start_date = CURRENT_TIMESTAMP, status = 'Working' WHERE work_id = '$work_id'";


if ($con->query($sql)) {

    $_SESSION['success'] = "Work started successfully!";

}


header(
    "Location: Tasks.php?project_id="
    . $project_id
    . "&module_id="
    . $module_id
);

exit();

?>