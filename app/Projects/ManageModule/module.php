<?php
// ManageModule.php

session_start();

include (substr(__FILE__, 0, strrpos(__FILE__, '/') + 1) . "../../inc/db.php");



if(!isset($_SESSION['logged_in'])){
    header("Location: ../../login.php");
    exit();
}

$name = $_SESSION['name'];
$user_id = $_SESSION['user_id'];

if(isset($_POST['module_name'])){

    $module = $_POST['module_name'];
    if(isset($_GET['project_id'])){
        $project_id = $_GET['project_id'];
    }else{
        die("Project ID not found.");
    }

    $sql = "INSERT INTO `ExcellentIts`.`tbl_module` (`project_id`, `module_name`, `dt`) VALUES ('$project_id', '$module', CURRENT_TIMESTAMP)";

    if ($con->query($sql) == true){

        $_SESSION['success'] = "Module added successfully!";

        header("Location: module.php?project_id=".$project_id."&success=true");
        exit();
    }
    else{
        echo "Error: $sql <br> $con->error";    
    }

    $con->close();

}

if(isset($_GET['id'])){

    $id = (int)$_GET['id'];
    $project_id = (int)$_GET['project_id'];

    $sql = "DELETE FROM tbl_module WHERE module_id = $id";

    if($con->query($sql)){
        $_SESSION['success'] = "Module deleted successfully!";
    }else{
        $_SESSION['success'] = "Error deleting module!";
    }

    header("Location: module.php?project_id=".$project_id);
    exit();
}

$project_id = (int)$_GET['project_id'];
// Project Details
$sql_project = "SELECT * FROM tbl_projects WHERE project_id ='$project_id'";
$result_project = $con->query($sql_project);
$project = $result_project->fetch_assoc();

// Module List
$sql_module = "SELECT * FROM tbl_module
               WHERE project_id='$project_id'
               ORDER BY module_id ASC";

$result = $con->query($sql_module);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ManageModules</title>
    <link rel="stylesheet" href="../../Assets/css/style.css">
    <link rel="stylesheet" href="../../Assets/css/module.css">
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
        <button class="btn" onclick = "window.location.href='../ManageProjects/ManageProjects.php'">Back</button>
        <h1><?php echo $project['name']; ?></h1>
        <button class="btn" onclick = "openModal()">Add Module</button>
    </div>

    <div class="project-card">
        <?php
            $count = 1;
            if ($result->num_rows > 0){
                while($row = $result->fetch_assoc()){

                    echo "<div class='card' onclick=\"window.location.href='../ManageTasks/addTask.php?project_id=" . $project_id . "&module_id=" . $row['module_id'] . "'\">";

                    echo "<a href=' Module.php?id=" . $row['module_id'] . "&project_id=" . $project_id . "' class='delete-link' onclick='return confirm(\"Are you sure you want to delete this module?\")'><i class='fas fa-xmark'></i></a>";
                    echo "<h5>" . $count . ".</h5>";
                    echo "<h5>" . $row['module_name'] . "</h5>";
                    echo "</div>";
                    $count++;
                }
            }else{
                echo "<p>No modules found.</p>";
            }

        ?>

    </div>

   

    <!-- MODAL POPUP -->
    <div id="projectModal" class="modal">

        <div class="modal-box">

            <h2>Add Module</h2>

            <form method="POST" action="module.php?project_id=<?php echo $project_id; ?>">

                <label>Module Name</label>
                <input type="text" name="module_name" required>

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
