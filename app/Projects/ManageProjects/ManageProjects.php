<?php
// ManageProjects.php

include (substr(__FILE__, 0, strrpos(__FILE__, '/') + 1) . "../../inc/db.php");

session_start();

if(!isset($_SESSION['logged_in'])){
    header("Location: ../../login.php");
    exit();
}

$name = $_SESSION['name'];

// $insert = isset($_GET['success']);

if(isset($_POST['name'])){

    $name = $_POST['name'];

    $sql = "INSERT INTO `tbl_projects` SET `name` = '$name', `dt` = CURRENT_TIMESTAMP ";

     if ($con->query($sql) == true){

        $_SESSION['success'] = "Project added successfully!";

        header("Location: ManageProjects.php?success=true");
        exit();
    }
    else{
        echo "Error: $sql <br> $con->error";    
    }

    $con->close();

}

if(isset($_GET['id'])){

    $id = (int)$_GET['id'];

    $sql_check = " SELECT COUNT(*) AS total FROM tbl_module WHERE project_id = $id";

    $result_check = ($con->query($sql_check));
    $row_check = $result_check->fetch_assoc();

    if ($row_check['total'] > 0){
        $_SESSION['success'] = "cannot delete project! There are modules associated with this project.";
    }else {
        
        $sql = "DELETE FROM tbl_projects WHERE project_id = $id";

    if($con->query($sql)){
        $_SESSION['success'] = "Project deleted successfully!";
    }else{
        $_SESSION['success'] = "Error deleting project!";
    }


    }

    

    header("Location: ManageProjects.php");
    exit();
}



$sql = "SELECT * FROM `ExcellentIts`.`tbl_projects` ORDER BY `project_id` ASC";
$result = $con->query($sql);


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ManageProjects</title>
    <link rel="stylesheet" href="../../Assets/css/style.css">
    <link rel="stylesheet" href="../../Assets/css/manageProjects.css">
    <!-- <link rel="stylesheet" href="../../Assets/css/dashboard.css"> -->
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>        
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" integrity="sha512-..." crossorigin="anonymous" referrerpolicy="no-referrer" />

</head>
<body>


    <header>
        <div class="logo">
            <img src="../../Assets/images/logo2.png" alt="ExcellentIts Logo">
        </div>
        <div class="nav">

            <p>Welcome,<strong><?php echo $name; ?></strong>!</p>

            <a href="../../logout.php?logout=true">Logout</a>

        </div>
    </header>

    <img src="../../Assets/images/bg1.webp" alt="Background Image" class="bg">

    <?php

        if (isset($_SESSION['success'])){
            echo "<p class='submitmsg' id='submitmsg'>" . $_SESSION['success'] . "</p>";
            unset($_SESSION['success']);
        }
    ?>
    <div class="container">
        <button class="btn" onclick = "window.location.href='../../dashboard.php'">Back</button>
        <h1>Manage Projects</h1>
        <button class="btn" onclick = "openModal()">Add Project</button>
    </div>

    <div class="project-card">
        <?php
            if ($result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    echo "<div class='card' onclick=\"window.location.href='../ManageModule/module.php?project_id=".$row['project_id']."'\">";

                    echo "<a href=' ManageProjects.php?id=" . $row['project_id'] . "' class='delete-link' onclick='return confirm(\"Are you sure you want to delete this project?\")'><i class='fas fa-xmark'></i></a>";
                    echo "<h3>" . $row['name'] . "</h3>";
                    echo "</div>";
                }
            }else{
                echo "<p>No projects found.</p>";
            }

        ?>

    </div>

   

    <!-- MODAL POPUP -->
    <div id="projectModal" class="modal">

        <div class="modal-box">

            <h2>Add Project</h2>

            <form method="POST" action="ManageProjects.php">

                <label>Name</label>
                <input type="text" name="name" required>

                <div class="modal-actions">
                    <button type="submit" class="btn">Add</button>
                    <button type="button" class="btn" onclick="closeModal()">Cancel</button>
                </div>

            </form>

        </div>

    </div>
    
    <script>
    function openModal(){
        document.getElementById("projectModal").style.display = "flex";
    }

    function closeModal(){
        document.getElementById("projectModal").style.display = "none";
    }
    function openEditModal(){
        document.getElementById("projectModal").style.display = "flex";
    }
    </script>
    <script src="../../Assets/js/Common.js"></script>

</body>
</html>
