<?php

session_start();

include (substr(__FILE__, 0, strrpos(__FILE__, '/') + 1) . "../../inc/db.php");


// 1. Check Login
if (!isset($_SESSION['logged_in'])) {
    header("Location: ../../login.php");
    exit();
}

$name = $_SESSION['name'];


// 2. Get Project ID and Module ID
$project_id = (int)$_GET['project_id'];
$module_id = (int)$_GET['module_id'];

$created_by = $_SESSION['user_id'];


// 3. Get Module Details
$sql_module = "SELECT * FROM tbl_module 
               WHERE module_id='$module_id' 
               AND project_id='$project_id'";

$result_module = $con->query($sql_module);

if ($result_module->num_rows == 0) {
    die("Module not found.");
}

$module = $result_module->fetch_assoc();

$uploadDir = "../../uploads/tasks/";

if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

$image_path = "";
if (isset($_FILES['task_file']) && isset($_FILES['task_file']['name']) && !empty($_FILES['task_file']['name'])) {
    $image_path = $uploadDir . basename($_FILES['task_file']['name']);
}

// 4. Add Task
if (isset($_POST['task_title'])) {

    $taskTitle = $_POST['task_title'];
    $taskDescription = $_POST['task_description'];
    $status = $_POST['status'];

    $sql = "INSERT INTO tbl_tasks
            (project_id, module_id, task_title, task_description, task_file, submitted_date, status, created_by)
            VALUES
            ('$project_id', '$module_id', '$taskTitle', '$taskDescription', '$image_path', CURRENT_TIMESTAMP, '$status', '$created_by')";

    if ($con->query($sql) === TRUE) {

        $_SESSION['success'] = "Task added successfully!";

        header("Location: addTask.php?project_id=$project_id&module_id=$module_id");

        exit();

    } else {

        echo "Error: " . $con->error;

    }
}


// 5. Get All Tasks
$sql_tasks = "SELECT * FROM tbl_tasks
              WHERE module_id='$module_id'
              AND project_id='$project_id'
              ORDER BY task_id DESC";

$result_tasks = $con->query($sql_tasks);

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Tasks</title>
    <link rel="stylesheet" href="../../Assets/css/style.css">
    <link rel="stylesheet" href="../../Assets/css/task.css">
    <link rel="stylesheet" href="../../Assets/css/addTask.css">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">
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
        // Display the success message once after adding a task.
        if(isset($_SESSION['success'])){
            echo "<p class='submitmsg' id='submitmsg'>" . $_SESSION['success'] . "</p>";
            unset($_SESSION['success']);
        }
    ?>

    <div class="container">
        <button class="btn" onclick="window.location.href='../ManageModule/module.php?project_id=<?php echo $project_id; ?>'">Back</button>
        <h1><?php echo $module['module_name']; ?></h1>
        <button class="btn" onclick="openTaskModal()">Add Task</button>
    </div>



    <div class="project-card">
        <?php
            $count = 1;
            if ($result_tasks->num_rows > 0){
                while($row = $result_tasks->fetch_assoc()){

                    echo "<div class='card' onclick=\"window.location.href='../ManageTasks/addTask.php?project_id=" . $project_id . "&module_id=" . $row['module_id'] . "'\">";

                    echo "<a href=' addTask.php?id=" . $row['module_id'] . "&project_id=" . $project_id . "' class='delete-link' onclick='return confirm(\"Are you sure you want to delete this module?\")'><i class='fas fa-xmark'></i></a>";
                    echo "<h5>" . $count . ".</h5>";
                    echo "<h5>" . $row['task_title'] . "</h5>";
                    echo "<h5>" . $row['task_description'] . "</h5>";
                    echo "<h5>File: " . $row['task_file'] . "</h5>";
                    echo "<h5>Status: " . ucfirst($row['status']) . "</h5>";
                    echo "</div>";
                    $count++;
                }
            }else{
                echo "<p>No task found.</p>";
            }

        ?>

    </div>

    <!-- Add Task Modal -->
    <div id="taskModal" class="modal">

        <div class="modal-box">

            <h2>Add Task</h2>

            <form method="POST" action="addTask.php?module_id=<?php echo $module_id; ?>&project_id=<?php echo $project_id; ?>" enctype="multipart/form-data">

                <label>Task Title</label>
                <input type="text" name="task_title" required>

                <label>Task Description</label>
                <textarea name="task_description" required></textarea>

                <label>Task File (Optional)</label>
                <input type="file" name="task_file" accept=".pdf,.doc,.docx,.txt,.jpg,.jpeg,.png">

                <label>Task Status</label>
                <select name="status">
                    <option value="pending">Pending</option>
                    <option value="in_progress">In Progress</option>
                    <option value="completed">Completed</option>
                </select>

                <div class="modal-actions">
                    <button type="submit" class="btn">Add</button>
                    <button type="button" class="btn" onclick="closeTaskModal()">Cancel</button>
                </div>

            </form>

        </div>

    </div>


    <script>
    // Open and close the Add Task modal.
    function openTaskModal(){
        document.getElementById("taskModal").style.display = "flex";
    }

    function closeTaskModal(){
        document.getElementById("taskModal").style.display = "none";
    }

    // Show or hide details for the selected task card.
    function toggleTask(id){
        var details = document.getElementById("task-" + id);

        if(details.style.display === "block"){
            details.style.display = "none";
        }
        else{
            details.style.display = "block";
        }
    }
    </script>
    <script src="../../Assets/js/Common.js"></script>

</body>
</html>
