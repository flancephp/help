<?php

// connection to database

$server = "mysql";
$username = "root";
$password = "root";
$database = "emp_management";

$con = new mysqli($server, $username, $password, $database);

if ($con->connect_errno) {
    die("Failed to connect to MySQL: " . $con->connect_errno);
}
