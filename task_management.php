<?php
session_start();
include('db_connection.php');

// Redirect to login page if not logged in
if (!isset($_SESSION['user_email'])) {
    header("Location: index.php");
    exit();
}

// Include your common header, navigation, and other dashboard content
include('admin_dashboard_header.php');
// Include your common footer
include('dashboard_footer.php');

function calculateProgress($task)
{
    // Calculate progress based on task status
    return ($task['status'] === 'finished') ? 100 : 0;
}

function calculateGroupProgress($conn, $groupId)
{
    $totalTasks = 0;
    $completedTasks = 0;

    $tasksSql = "SELECT * FROM tasks WHERE group_id = '$groupId'";
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

    // Calculate the group progress percentage
    return ($totalTasks > 0) ? ($completedTasks / $totalTasks) * 100 : 0;
}

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

    <!-- Task Management Section -->
    <div id="taskManagementSection">
        <h2>Task Management</h2>

        <script>
            // Periodically update the progress bars
            setInterval(function() {
                // Reload the content of the task management section using jQuery
                $("#taskManagementSection").load("dashboard.php #taskManagementSection");
            }, 10000); // Update every 10 seconds (adjust as needed)
        </script>

        <!-- Display existing tasks -->
        <?php
        $groupsSql = "SELECT * FROM groups";
        $groupsResult = $conn->query($groupsSql);

        if ($groupsResult->num_rows > 0) {
            while ($group = $groupsResult->fetch_assoc()) {
                //echo '<h3>' . $group['name'] . ' Progress</h3>';
                echo '<h3><a href="task_management.php?groupId=' . $group['id'] . '">' . $group['name'] . '</a></h3>';

                // Display tasks for the selected group
                $groupId = $group['id'];
                $tasksSql = "SELECT * FROM tasks WHERE group_id = '$groupId'";
                $tasksResult = $conn->query($tasksSql);

                // Overall Progress Bar
                if ($tasksResult->num_rows > 0) {
                    echo '<div class="progress mt-4">';
                    $overallProgress = calculateGroupProgress($conn, $group['id']);
                    $progressColor = ($overallProgress > 50) ? 'bg-success' : 'bg-danger';
                    echo '<div class="progress-bar ' . $progressColor . '" role="progressbar" 
                    style="width: ' . $overallProgress . '%" aria-valuenow="' . $overallProgress . '" 
                    aria-valuemin="0" aria-valuemax="100">' . $overallProgress . '%</div>';
                    echo '</div>';
                } else {
                    echo 'No tasks found for this group.';
                }

                // Check if a specific group is selected
                if (isset($_GET['groupId']) && $_GET['groupId'] == $group['id']) {
                    $groupId = $_GET['groupId'];

                    // Display tasks for the selected group
                    $tasksSql = "SELECT * FROM tasks WHERE group_id = '$groupId'";
                    $tasksResult = $conn->query($tasksSql);

                    if ($tasksResult->num_rows > 0) {
                        while ($task = $tasksResult->fetch_assoc()) {
                            echo '<div class="card">';
                            echo '<div class="container">';
                            echo '<h4><b>' . $task['name'] . '</b></h4>';
                            // Add progress bar element
                            echo '<div class="progress">';
                            echo '<div class="progress-bar" role="progressbar" style="width: ' . calculateProgress($task) . '%" aria-valuenow="' .
                                calculateProgress($task) . '" aria-valuemin="0" aria-valuemax="100">' . calculateProgress($task) . '%</div>';
                            echo '</div>';
                            echo '<p>Step: ' . $task['step'] . '</p>';
                            echo '<p>About this step: ' . $task['about'] . '</p>';
                            echo '<p>Step deadline: ' . $task['deadline'] . '</p>';
                            echo '<p>Step report: ' . $task['report'] . '</p>';
                            echo '</div>';
                            echo '</div>';
                        }
                    }
                }
            }
        } else {
            echo 'No groups found.';
        }
        ?>

    </div>

</body>

</html>