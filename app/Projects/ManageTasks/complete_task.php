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



/* --------------------------------
   COMPLETE WORK
-------------------------------- */

$sql = "UPDATE tbl_task_work SET
            completed_date = CURRENT_TIMESTAMP,
            status = 'Completed'

        WHERE work_id = '$work_id'";


$con->query($sql);



/* --------------------------------
   CHECK DEVELOPER
-------------------------------- */

$sql_dev = "SELECT status FROM tbl_task_work
            WHERE task_id = '$task_id'
            AND role = 'Developer'";

$result_dev = $con->query($sql_dev);

$developer = $result_dev->fetch_assoc();



/* --------------------------------
   CHECK TESTER
-------------------------------- */

$sql_tester = "SELECT status
               FROM tbl_task_work
               WHERE task_id = '$task_id'
               AND role = 'Tester'";

$result_tester = $con->query($sql_tester);

$tester = $result_tester->fetch_assoc();



/* --------------------------------
   UPDATE TASK STATUS
-------------------------------- */

if (
    $developer['status'] == 'Completed'
    &&
    $tester['status'] == 'Completed'
) {

    $task_status = "Completed";

} elseif (
    $developer['status'] == 'Completed'
) {

    $task_status = "Testing";

} else {

    $task_status = "Developer Working";

}


$sql_task = "UPDATE tbl_tasks

             SET status = '$task_status'

             WHERE task_id = '$task_id'";

$con->query($sql_task);



$_SESSION['success'] = "Work completed successfully!";



header(
    "Location: Tasks.php?project_id="
    . $project_id
    . "&module_id="
    . $module_id
);

exit();

?>






<?php
// addTask.php

include (substr(__FILE__, 0, strrpos(__FILE__, '/') + 1) . "../../inc/db.php");

session_start();

if(!isset($_SESSION['logged_in'])){
    header("Location: ../../login.php");
    exit();
}

$name = $_SESSION['name'];

// Get project and module IDs from the URL.
if(!isset($_GET['project_id']) || !isset($_GET['module_id'])){
    die("Project ID or Module ID not found.");
}

$project_id = (int)$_GET['project_id'];
$module_id = (int)$_GET['module_id'];

// Add a new task when the Add Task form is submitted.
if(isset($_POST['add_task'])){

    $task_title = $_POST['task_title'];
    $task_description = $_POST['task_description'];

    $sql = "INSERT INTO tbl_tasks
            SET project_id = '$project_id',
                module_id = '$module_id',
                task_title = '$task_title',
                task_description = '$task_description',
                status = 'Pending',
                created_by = '1'";

    if($con->query($sql) == true){

        // Get the ID of the task that was just added.
        $task_id = $con->insert_id;

        // Create the Developer work record for this task.
        $sql_dev = "INSERT INTO tbl_task_work (task_id, role, status)
                    VALUES ('$task_id', 'Developer', 'Waiting')";
        $con->query($sql_dev);

        // Create the Tester work record for this task.
        $sql_tester = "INSERT INTO tbl_task_work (task_id, role, status)
                       VALUES ('$task_id', 'Tester', 'Waiting')";
        $con->query($sql_tester);

        $_SESSION['success'] = "Task added successfully!";

        // Reload this page so the new task appears in the task list.
        header("Location: addTask.php?project_id=".$project_id."&module_id=".$module_id);
        exit();
    }
    else{
        echo "Error: $sql <br> $con->error";
    }
}

// Get the current module name for the page heading.
$sql_module = "SELECT * FROM tbl_module WHERE sno2 = '$module_id'";
$result_module = $con->query($sql_module);
$module = $result_module->fetch_assoc();

// Get all tasks of the selected project and module.
$sql_tasks = "SELECT * FROM tbl_tasks
              WHERE project_id = '$project_id'
              AND module_id = '$module_id'
              ORDER BY task_id ASC";
$result_tasks = $con->query($sql_tasks);

?>

<!-- Show every task created for the selected module. -->
    <div class="task-container">

        <?php
        $count = 1;

        if($result_tasks->num_rows > 0){
            while($task = $result_tasks->fetch_assoc()){

                $task_id = $task['task_id'];

                // Get the Developer work status for the current task.
                $sql_dev = "SELECT * FROM tbl_task_work
                            WHERE task_id = '$task_id'
                            AND role = 'Developer'";
                $result_dev = $con->query($sql_dev);
                $developer = $result_dev->fetch_assoc();

                // Get the Tester work status for the current task.
                $sql_tester = "SELECT * FROM tbl_task_work
                               WHERE task_id = '$task_id'
                               AND role = 'Tester'";
                $result_tester = $con->query($sql_tester);
                $tester = $result_tester->fetch_assoc();
        ?>

            <div class="task-card">
                <div class="task-main-row">
                    <div class="task-number">#<?php echo $count; ?></div>
                    <div class="task-title"><strong><?php echo htmlspecialchars($task['task_title']); ?></strong></div>
                    <div class="task-date"><?php echo date("d M Y h:i A", strtotime($task['submitted_date'])); ?></div>
                    <div class="task-status"><?php echo $task['status']; ?></div>
                    <button class="expand-btn" onclick="toggleTask(<?php echo $task_id; ?>)"><i class="fa-solid fa-chevron-down"></i></button>
                </div>

                <!-- Hidden area showing task description and work progress. -->
                <div id="task-<?php echo $task_id; ?>" class="task-details">
                    <div class="task-description">
                        <h3>Task Description</h3>
                        <p><?php echo nl2br(htmlspecialchars($task['task_description'])); ?></p>
                    </div>

                    <!-- Developer can start and complete work first. -->
                    <div class="work-row">
                        <div class="work-role">
                            <i class="fa-solid fa-code"></i>
                            <strong>Developer</strong>
                        </div>
                        <div class="work-status">
                            <?php
                            if($developer['status'] == 'Waiting'){
                                echo "<span class='waiting'>Waiting</span>";
                            }
                            elseif($developer['status'] == 'Working'){
                                echo "<span class='working'>Working</span>";
                            }
                            elseif($developer['status'] == 'Completed'){
                                echo "<span class='completed'>Completed</span>";
                            }
                            ?>
                        </div>
                        <div class="work-time">
                            <?php
                            if($developer['start_date']){
                                echo "<p>Start: " . date("d M Y h:i A", strtotime($developer['start_date'])) . "</p>";
                            }
                            if($developer['completed_date']){
                                echo "<p>Completed: " . date("d M Y h:i A", strtotime($developer['completed_date'])) . "</p>";
                            }
                            ?>
                        </div>
                        <div class="work-button">
                            <?php
                            if($developer['status'] == 'Waiting'){
                            ?>
                                <a class="start-btn" href="start_task.php?work_id=<?php echo $developer['work_id']; ?>&task_id=<?php echo $task_id; ?>&project_id=<?php echo $project_id; ?>&module_id=<?php echo $module_id; ?>">Start Work</a>
                            <?php
                            }
                            elseif($developer['status'] == 'Working'){
                            ?>
                                <a class="complete-btn" href="complete_task.php?work_id=<?php echo $developer['work_id']; ?>&task_id=<?php echo $task_id; ?>&project_id=<?php echo $project_id; ?>&module_id=<?php echo $module_id; ?>">Mark Completed</a>
                            <?php
                            }
                            ?>
                        </div>
                    </div>

                    <!-- Tester work is available only after Developer work is complete. -->
                    <div class="work-row">
                        <div class="work-role">
                            <i class="fa-solid fa-vial"></i>
                            <strong>Tester</strong>
                        </div>
                        <div class="work-status">
                            <?php
                            if($tester['status'] == 'Waiting'){
                                if($developer['status'] != 'Completed'){
                                    echo "<span class='locked'>Waiting for Developer</span>";
                                }
                                else{
                                    echo "<span class='waiting'>Ready for Testing</span>";
                                }
                            }
                            elseif($tester['status'] == 'Working'){
                                echo "<span class='working'>Testing</span>";
                            }
                            elseif($tester['status'] == 'Completed'){
                                echo "<span class='completed'>Completed</span>";
                            }
                            ?>
                        </div>
                        <div class="work-time">
                            <?php
                            if($tester['start_date']){
                                echo "<p>Start: " . date("d M Y h:i A", strtotime($tester['start_date'])) . "</p>";
                            }
                            if($tester['completed_date']){
                                echo "<p>Completed: " . date("d M Y h:i A", strtotime($tester['completed_date'])) . "</p>";
                            }
                            ?>
                        </div>
                        <div class="work-button">
                            <?php
                            if($tester['status'] == 'Waiting' && $developer['status'] == 'Completed'){
                            ?>
                                <a class="start-btn" href="start_task.php?work_id=<?php echo $tester['work_id']; ?>&task_id=<?php echo $task_id; ?>&project_id=<?php echo $project_id; ?>&module_id=<?php echo $module_id; ?>">Start Testing</a>
                            <?php
                            }
                            elseif($tester['status'] == 'Working'){
                            ?>
                                <a class="complete-btn" href="complete_task.php?work_id=<?php echo $tester['work_id']; ?>&task_id=<?php echo $task_id; ?>&project_id=<?php echo $project_id; ?>&module_id=<?php echo $module_id; ?>">Testing Completed</a>
                            <?php
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>

        <?php
                $count++;
            }
        }
        else{
            echo "<p class='no-task'>No tasks found.</p>";
        }
        ?>
    </div>

    <!-- Modal popup for adding a task. -->
    <div id="taskModal" class="modal">
        <div class="modal-box">
            <h2>Add New Task</h2>

            <form method="POST" action="addTask.php?project_id=<?php echo $project_id; ?>&module_id=<?php echo $module_id; ?>">
                <label>Task No.</label>
                <input type="text" value="Auto Generated" disabled>

                <label>Task Title</label>
                <input type="text" name="task_title" required>

                <label>Task Description</label>
                <textarea name="task_description" rows="5" required></textarea>

                <div class="modal-actions">
                    <button type="submit" name="add_task" class="btn">Save</button>
                    <button type="button" class="btn" onclick="closeTaskModal()">Cancel</button>
                </div>
            </form>
        </div>
    </div>