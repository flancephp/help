<?php
if (isset($_GET['logout'])){
    session_start();
    session_destroy();
    header("Location: login.php?logged_out=true");
    exit();
}
?>