<?php
// Include your database connection
include("db_connection.php");

if (isset($_GET['groupId'])) {
    $groupId = $_GET['groupId'];

    // Update the progress bar for the specified group
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

        // Display the updated overall progress bar
        echo '<div class="progress-bar" role="progressbar" style="width: ' . $overallProgress . '%" aria-valuenow="' . $overallProgress . '" aria-valuemin="0" aria-valuemax="100">' . $overallProgress . '%</div>';
    } else {
        echo '<p>No tasks available for Group ID: ' . $groupId . '</p>';
    }
}

// Close the database connection
$conn->close();
?>
