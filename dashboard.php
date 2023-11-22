<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Project Management System</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
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

    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="#">Project Management</a>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item active">
                    <a class="nav-link" href="#">Home </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Grouping</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Task Management</a>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Welcome Message Box -->
    <div class="welcome-box">
        <?php
        session_start();
        if (isset($_SESSION['username'])) {
            echo '<p>Hello, ' . $_SESSION['username'] . '!</p>';
        } else {
            header("Location: index.php");
            exit();
        }

        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "project_management_system";

        $conn = new mysqli($servername, $username, $password, $dbname);

        ?>
    </div>


<!-- Grouping Section -->
<div>
    <!-- Grouping Section -->
<div class="row">
    <h2>Group Information</h2>

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

            echo '<div class="col-md-3">'; // Each card takes 3 columns in medium-sized screens
            echo '<div class="card">';
            echo '<img src="grp.jpg" alt="Group Avatar" style="width:100%">';
            echo '<div class="container">';
            echo '<h4><b>' . $group['name'] . '</b></h4>';
            echo '<p>ID: ' . $group['id'] . '</p>';
            echo '<p>Course Name: ' . $group['course_name'] . '</p>';
            echo '<p>Size: ' . $group['size'] . '</p>';

            // Display group members
            $groupId = $group['id'];
            $membersSql = "SELECT * FROM group_members WHERE group_id = $groupId";
            $membersResult = $conn->query($membersSql);

            if ($membersResult->num_rows > 0) {
                echo '<p>Group Members:</p>';
                echo '<ul>';
                while ($member = $membersResult->fetch_assoc()) {
                    echo '<li>' . $member['member_name'] . '</li>';
                }
                echo '</ul>';
            } else {
                echo '<p>No group members.</p>';
            }

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

    
</div>






    <!-- Grouping Section -->
    <!-- Grouping Section -->
<div>
    <h2>Group Information</h2>

    <!-- Display existing groups -->
    <?php
    // Display existing groups
    $sql = "SELECT * FROM groups";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($group = $result->fetch_assoc()) {
            echo '<div class="card">';
            echo '<img src="grp.jpg" alt="Group Avatar" style="width:100%">';
            echo '<div class="container">';
            echo '<h4><b>' . $group['name'] . '</b></h4>';
            echo '<p>ID: ' . $group['id'] . '</p>';
            echo '<p>Course Name: ' . $group['course_name'] . '</p>';
            echo '<p>Size: ' . $group['size'] . '</p>';

            // Display group members
            $groupId = $group['id'];
            $membersSql = "SELECT * FROM group_members WHERE group_id = $groupId";
            $membersResult = $conn->query($membersSql);

            if ($membersResult->num_rows > 0) {
                echo '<p>Group Members:</p>';
                echo '<ul>';
                while ($member = $membersResult->fetch_assoc()) {
                    echo '<li>' . $member['member_name'] . '</li>';
                }
                echo '</ul>';
            } else {
                echo '<p>No group members.</p>';
            }

            // Add links for CRUD operations
            echo '<p>';
            echo '<a href="edit_group.php?id=' . $group['id'] . '">Edit</a> | ';
            echo '<a href="delete_group.php?id=' . $group['id'] . '">Delete</a>';
            echo '</p>';

            echo '</div>'; // Close container
            echo '</div>'; // Close card
        }
    } else {
        echo '<p>No groups available.</p>';
    }
    ?>
</div>


    <!-- Task Management Section -->
    
<<!-- Task Management Section -->
<div>
    <h2>Task Management</h2>

    <!-- Display existing tasks -->
    <?php
    $tasksSql = "SELECT * FROM tasks";
    $tasksResult = $conn->query($tasksSql);

    if ($tasksResult->num_rows > 0) {
        while ($task = $tasksResult->fetch_assoc()) {
            echo '<div class="card">';
            echo '<div class="container">';
            echo '<h4><b>' . $task['name'] . '</b></h4>';
            echo '<p>Step: ' . $task['step'] . '</p>';
            echo '<p>About this step: ' . $task['about'] . '</p>';
            echo '<p>Step deadline: ' . $task['deadline'] . '</p>';
            echo '<p>Step report: ' . $task['report'] . '</p>';
            echo '<p>Attachments: ' . $task['attachments'] . '</p>';

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
    ?>
</div>

    

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>

</html>