<?php
session_start();
include('db_connection.php');

//Redirect to login page if not logged in
if (!isset($_SESSION['user_email'])) {
    header("Location: index.php");
    exit();
}



// Include your common header, navigation, and other dashboard content
include('dashboard_header.php');
// Include your common footer
include('dashboard_footer.php');

?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <!-- Include Bootstrap CSS for styling -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <!-- Include jQuery and Bootstrap JS for dynamic updates -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>


    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        .welcome-box {
            background-color: #f8d7da;
            color: #721c24;
            padding: 15px;
            margin-bottom: 20px;
            border: 1px solid #f5c6cb;
            border-radius: 5px;
        }
    </style>

</head>

<body>
    <!-- Welcome Message Box -->
    <div class="welcome-box">
        <?php
        echo '<p>Hello, ' . $_SESSION['user_email'] . '. You are ' . $_SESSION['role'] . '!</p>';
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "project_management_system";

        $conn = new mysqli($servername, $username, $password, $dbname);

        ?>
    </div>


    <!-- Grouping Section -->
    <div class="row">
        <h2>Group Information</h2>
        <br>
        <br>

        <?php
        // Assume you have a 'groups' table with columns 'id', 'name', 'course_name', 'size'
        $sql = "SELECT * FROM groups";
        $result = $conn->query($sql);

        $count = 0; // Initialize counter
        
        if ($result->num_rows > 0) {
            while ($group = $result->fetch_assoc()) {
                if ($count % 4 == 0) {
                    // Start a new row for every 4 cards
                    echo '</div><div class="row">';
                }

                echo '<div class="col-md-4">'; // Each card takes 4 columns in medium-sized screens
                echo '<div class="card">';
                echo '<img src="grp.jpg" alt="Group Avatar" style="width:50%">';
                echo '<div class="container">';
                echo '<h4><b>' . $group['name'] . '</b></h4>';
                echo '<p>Course ID: ' . $group['course_id'] . '</p>';
                echo '<p>Course Name: ' . $group['course_name'] . '</p>';
                echo '<p>Size: ' . $group['size'] . '</p>';
                echo '<p>Group member: ' . $group['user_email'] . '</p>';


                echo '</div>'; // Close container
                echo '</div>'; // Close card
                echo '</div>'; // Close column
                $count++;
            }

            // Close the last row
            echo '</div>';
        } else {
            echo '<p>No groups available.</p>';
        }
        ?>
    </div>
    <!-- End of Grouping Section -->






    <!-- Task Management Section -->
    <div id="taskManagementSection">
        <h2>Task Management</h2>
        <h3>Overall progress</h3>
        <br>

        <!-- Overall Progress Bar -->

    <?php
// Assuming you have a 'group_members' table to associate students with groups
$groupMembersSql = "SELECT group_id FROM group_members WHERE user_email = '{$_SESSION['user_email']}'";
$groupMembersResult = $conn->query($groupMembersSql);

$groupIds = array();
while ($groupMember = $groupMembersResult->fetch_assoc()) {
    $groupIds[] = $groupMember['group_id'];
}

// Display overall progress bar for each group the student belongs to
if (!empty($groupIds)) {
    foreach ($groupIds as $groupId) {
        $tasksSql = "SELECT * FROM tasks WHERE group_id = $groupId";
        $tasksResult = $conn->query($tasksSql);

        $totalTasks = 0;
        $completedTasks = 0;

        if ($tasksResult->num_rows > 0) {
            while ($task = $tasksResult->fetch_assoc()) {
                $totalTasks++;

                // Check if the task is completed
                if ($task['status'] === 'finished') {
                    $completedTasks++;
                }
            }

            // Calculate the overall progress percentage
            $overallProgress = ($totalTasks > 0) ? (($completedTasks / $totalTasks) * 100) : 0;

            // Choose the color based on overall progress
            $colorClass = ($overallProgress >= 50) ? 'bg-success' : 'bg-danger';

            // Display the overall progress bar with a unique ID
            echo '<div class="progress ' . $colorClass . '" style="height: 30px;">';
            echo '<div class="progress-bar" role="progressbar" style="width: ' . $overallProgress . '%" aria-valuenow="' . $overallProgress . '" aria-valuemin="0" aria-valuemax="100">' . $overallProgress . '%</div>';
            echo '</div>';
        } else {
            echo '<p>No tasks available for Group ID: ' . $groupId . '</p>';
        }
    }
} else {
    echo '<p>You are not a member of any group.</p>';
}
?>


<br>

    <?php
    // Assuming you have a 'group_members' table to associate students with groups
    $groupMembersSql = "SELECT group_id FROM group_members WHERE user_email = '{$_SESSION['user_email']}'";
    $groupMembersResult = $conn->query($groupMembersSql);

    $groupIds = array();
    while ($groupMember = $groupMembersResult->fetch_assoc()) {
        $groupIds[] = $groupMember['group_id'];
    }

    // Display tasks only for the groups the student belongs to
    if (!empty($groupIds)) {
        $groupIdsString = implode(',', $groupIds);

        $tasksSql = "SELECT tasks.*
                FROM tasks
                INNER JOIN groups ON tasks.group_id = groups.id
                WHERE groups.id IN ($groupIdsString)";

        $tasksResult = $conn->query($tasksSql);

        if ($tasksResult->num_rows > 0) {
            while ($task = $tasksResult->fetch_assoc()) {
                echo '<div class="card">';
                echo '<div class="container">';
                echo '<h4><b>' . $task['name'] . '</b></h4>';
                // Add progress bar element
                echo '<div class="progress">';
                echo '<div class="progress-bar" role="progressbar" style="width: ' . calculateProgress($task) . '%" aria-valuenow="' . calculateProgress($task) . '" aria-valuemin="0" aria-valuemax="100">' . calculateProgress($task) . '%</div>';
                echo '</div>';
                echo '<p>Step: ' . $task['step'] . '</p>';
                echo '<p>About this step: ' . $task['about'] . '</p>';
                echo '<p>Step deadline: ' . $task['deadline'] . '</p>';
                echo '<p>Step report: ' . $task['report'] . '</p>';
                echo '<p>Attachments: ' . $task['attachments'] . '</p>';
                echo '<p>Status: ' . $task['status'] . '</p>'; // Display task status
                // Add links for CRUD operations
                echo '<p>';
                echo '<a href="edit_task.php?id=' . $task['id'] . '">Edit</a> | ';
                echo '<a href="delete_task.php?id=' . $task['id'] . '">Delete</a>';
                echo '</p>';
                echo '</div>'; // Close container
                echo '</div>'; // Close card
            }
        } else {
            echo '<p>No tasks available for your groups.</p>';
        }
    } else {
        echo '<p>You are not a member of any group.</p>';
    }

    // Function to calculate the progress percentage for a task
    function calculateProgress($task)
    {
        // Calculate progress based on task status
        return ($task['status'] === 'finished') ? 100 : 0;
    }
    ?>


<!-- Add Task Button -->
<div class="text-center mt-3">
    <button class="btn btn-success" data-toggle="modal" data-target="#addTaskModal">Add Task</button>
</div>


   <!-- Add Task Modal -->
<div class="modal" id="addTaskModal" tabindex="-1" role="dialog" aria-labelledby="addTaskModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addTaskModalLabel">Add Task</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Include the form to add a new task (similar to edit_task.php) -->
                <form action="create_task.php" method="post">
                    <div class="modal-body">
                        <label for="task_name">Task Name:</label>
                        <input type="text" name="name" required>

                        <label for="step">Step:</label>
                        <input type="text" name="step" required>

                        <label for="about">About this step:</label>
                        <textarea name="about" required></textarea>

                        <label for="deadline">Step deadline:</label>
                        <input type="datetime-local" name="deadline" required>

                        <label for="report">Step report:</label>
                        <textarea name="report" required></textarea>

                        <label for="attachment">Attachments:</label>
                        <input type="text" name="attachment" required>

                        <!-- Add a hidden input field to pass the group_id -->
                        <input type="hidden" name="group_id" value="<?php echo $groupId; ?>">

                        <label for="status">Task Status:</label>
                        <select name="status">
                            <option value="processing">Processing</option>
                            <option value="finished">Finished</option>
                        </select>

                        <button type="submit" class="btn btn-primary">Add Task</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

    <?php
    function calculateOverallProgress($conn)
    {
        $totalTasks = 0;
        $completedTasks = 0;

        $tasksSql = "SELECT * FROM tasks";
        $tasksResult = $conn->query($tasksSql);

        if ($tasksResult->num_rows > 0) {
            while ($task = $tasksResult->fetch_assoc()) {
                $totalTasks++;

                // Check if the task is completed
                if ($task['status'] === 'finished') {
                    $completedTasks++;
                }
            }
        }

        // Calculate the overall progress percentage
        return ($totalTasks > 0) ? ($completedTasks / $totalTasks) * 100 : 0;
    }
    ?>


<script>
// Function to update progress bars using AJAX
function updateProgressBars() {
    <?php
    // Generate JavaScript code to fetch updated progress bars using AJAX
    if (!empty($groupIds)) {
        foreach ($groupIds as $groupId) {
            echo "$.get('update_progress.php?groupId=$groupId', function(data) {";
            echo "$('#progressBar_$groupId').html(data);";
            echo "});";
        }
    }
    ?>
}

// Load the progress bars when the page loads
$(document).ready(function () {
    updateProgressBars();

    // Periodically update the progress bars
    setInterval(function () {
        updateProgressBars();
    }, 10000); // Update every 10 seconds (adjust as needed)
});
</script>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>


</body>

</html>