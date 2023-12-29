<?php
session_start();
include('db_connection.php');

//Redirect to login page if not logged in
if (!isset($_SESSION['user_email'])) {
    header("Location: index.php");
    exit();
}

if ($_SESSION['role'] != 'student') {
    header("Location: index.php");
}

// Include your common header, navigation, and other dashboard content
include('stu_dashboard_header.php');
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
    <script>
        // Periodically update the progress bars
        setInterval(function() {
            // Reload the content of the task management section using jQuery
            $("#taskManagementSection").load("dashboard.php #taskManagementSection");
        }, 10000); // Update every 10 seconds (adjust as needed)
    </script>

    <style>
        body {
            font-family: Arial, sans-serif;
        }
    </style>

</head>

<body>
    <div class="container mt-5">
        <h2><?php echo $_GET['course_id'] ?></h2>
        <br>

        <?php
        // joined group
        $userEmail = $_SESSION['user_email'];
        $courseID = $_GET['course_id'];
        $joinedGroupsQuery = "SELECT * FROM groups
                      JOIN group_members ON groups.id = group_members.group_id
                      WHERE group_members.user_email = '$userEmail' AND groups.course_id = '$courseID'";
        $joinedGroupsResult = $conn->query($joinedGroupsQuery);

        $count = 0; // Initialize counter

        if ($joinedGroupsResult->num_rows > 0) {
            while ($group = $joinedGroupsResult->fetch_assoc()) {
                echo '<div class="col-md-3">'; // Each card takes 4 columns in medium-sized screens
                echo '<div class="card">';
                echo '<img src="grp.jpg" alt="Group Avatar" style="width:50%">';
                echo '<div class="container">';
                echo '<h4><b>' . $group['name'] . '</b></h4>';
                echo '<p>Size: ' . $group['size'] . '</p>';

                // Query to fetch group members
                $groupID = $group['id'];
                $groupMembersQuery = "SELECT * FROM group_members WHERE group_id = '$groupID'";
                $groupMembersResult = $conn->query($groupMembersQuery);

                echo '<p><b>Group Members:</b><br>';
                while ($member = $groupMembersResult->fetch_assoc()) {
                    echo $member['user_email'] . '<br>';
                }
                echo '</p>';

                echo '</div>'; // Close container
                echo '</div>'; // Close card
                echo '</div>'; // Close column

                // Display tasks
                $tasksSql = "SELECT * FROM tasks WHERE group_id = '$groupID'";
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
        } else {
            // not joined any groups, display available groups
            $availableGroupsQuery = "SELECT * FROM groups WHERE course_id='$courseID'";
            $availableGroupsResult = $conn->query($availableGroupsQuery);

            while ($group = $availableGroupsResult->fetch_assoc()) {
                if ($count % 4 == 0) {
                    // Start a new row for every 4 cards
                    echo '</div><br><div class="row justify-content-start">';
                }
                $target = $group['id'];
                echo '<div class="col-md-3">'; // Each card takes 4 columns in medium-sized screens
                echo '<div class="card">';
                echo '<img src="grp.jpg" alt="Group Avatar" style="width:50%">';
                echo '<div class="container">';
                echo '<h4><b>' . $group['name'] . '</b></h4>';
                echo '<p>Size: ' . $group['size'] . '</p>';
                echo '</div>'; // Close container
                //echo '<a href="join_group.php?course_id=' . $courseID . '&group_id=' . $group['id'] . '" class="btn btn-primary">Join Group</a>';
                // Check if the group is full
                $memberSql = "SELECT COUNT(*) FROM group_members WHERE group_id = '$target'";
                $memberResult = $conn->query($memberSql);
                $memberCount = $memberResult->fetch_row()[0]; // Fetch the count value

                if ($memberCount >= $group['size']) {
                    echo '<a href="stu_groups.php?course_id=' . $courseID . '" class="btn btn-secondary disabled">Group is already full</a>';
                } else {
                    echo '<a href="join_group.php?course_id=' . $courseID . '&group_id=' . $group['id'] . '" class="btn btn-primary">Join Group</a>';
                }
                echo '</div>'; // Close card
                echo '</div>'; // Close column
                $count++;
            }
        }
        ?>
    </div>


</body>

</html>