<?php
session_start();
include('db_connection.php');

//Redirect to login page if not logged in
if (!isset($_SESSION['user_email'])) {
    header("Location: index.php");
    exit();
}



// Customize dashboard content based on user role
if ($_SESSION['role'] == 'admin') {
    // Admin dashboard content goes here
    echo "<h2>Welcome, Admin!</h2>";
} elseif ($_SESSION['role'] == 'teacher') {
    // Teacher dashboard content goes here
    echo "<h2>Welcome, Teacher!</h2>";
} elseif ($_SESSION['role'] == 'student') {
    // Student dashboard content goes here
    echo "<h2>Welcome, Student!</h2>";
}
// Include your common header, navigation, and other dashboard content
include('admin_dashboard_header.php');
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


    <!-- create Grouping Section -->


    <form action="create_group.php" method="post">
        <label for="course_id">Course ID:</label>
        <input type="text" id="course_id" name="course_id" required>
        <label for="num_groups">Number of Groups:</label>
        <input type="number" id="num_groups" name="num_groups" min="1" required>
        <label for="group_size">Max Group Size:</label>
        <input type="number" id="group_size" name="group_size" min="1" required>
        <button type="submit">Create Groups</button>
    </form>
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

                // // Display group members
                // $groupId = $group['id'];
                // $membersSql = "SELECT * FROM group_members WHERE group_id = $groupId";
                // $membersResult = $conn->query($membersSql);
        
                // if ($membersResult->num_rows > 0) {
                //     echo '<p>Group Members:</p>';
                //     echo '<ul>';
                //     while ($member = $membersResult->fetch_assoc()) {
                //         echo '<li>' . $member['member_name'] . '</li>';
                //     }
                //     echo '</ul>';
                // } else {
                //     echo '<p>No group members.</p>';
                // }
        
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

    <!-- End of Grouping Section -->





    <!-- Task Management Section -->
    <div id="taskManagementSection">
        <h2>Task Management</h2>
        <h3>Overall progress</h3>

        <!-- Overall Progress Bar -->
        <div class="progress mt-4">
            <?php
            $overallProgress = calculateOverallProgress($conn);
            $progressColor = ($overallProgress > 50) ? 'bg-success' : 'bg-danger';
            ?>
            <div class="progress-bar <?php echo $progressColor; ?>" role="progressbar"
                style="width: <?php echo $overallProgress; ?>%;" aria-valuenow="<?php echo $overallProgress; ?>"
                aria-valuemin="0" aria-valuemax="100">
                <?php echo $overallProgress; ?>
            </div>
        </div>
        <br>
        <br>

        <script>
            // Periodically update the progress bars
            setInterval(function () {
                // Reload the content of the task management section using jQuery
                $("#taskManagementSection").load("dashboard.php #taskManagementSection");
            }, 10000); // Update every 10 seconds (adjust as needed)
        </script>


        <!-- Display existing tasks -->
        <?php
        $tasksSql = "SELECT * FROM tasks";
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
            echo '<p>No tasks available.</p>';
        }

        // Function to calculate the progress percentage for a task
        function calculateProgress($task)
        {
            // Calculate progress based on task status
            return ($task['status'] === 'finished') ? 100 : 0;
        }
        ?>



    </div>
    <!-- Add Task Button -->
    <div class="text-center mt-3">
        <button class="btn btn-success" data-toggle="modal" data-target="#addTaskModal">Add Task</button>
    </div>

    <!-- Add Task Modal -->
    <div class="modal" id="addTaskModal" tabindex="-1" role="dialog" aria-labelledby="addTaskModalLabel"
        aria-hidden="true">
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
                            <!-- Include the form to add a new task (similar to edit_task.php) -->
                            <form action="create_task.php" method="post">
                                <label for="task_name">Task Name:</label>
                                <input type="text" name="task_name" required>

                                <label for="step">Step:</label>
                                <input type="text" name="step" required>

                                <label for="about">About this step:</label>
                                <textarea name="about"></textarea>

                                <label for="deadline">Step deadline:</label>
                                <input type="datetime-local" name="deadline" required>

                                <label for="report">Step report:</label>
                                <textarea name="report"></textarea>

                                <label for="attachments">Attachments:</label>
                                <input type="text" name="attachments">

                                <label for="status">Task Status:</label>
                                <select name="status">
                                    <option value="processing">Processing</option>
                                    <option value="finished">Finished</option>
                                </select>

                                <button type="submit" class="btn btn-primary">Add Task</button>
                            </form>
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




</body>

</html>