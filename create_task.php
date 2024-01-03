<?php
session_start();

// Check if the user is logged in as a student
if (!isset($_SESSION['user_email']) || $_SESSION['role'] !== 'student') {
    header("Location: index.php");
    exit();
}

// Include your database connection
include_once("db_connection.php");

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get task input
    $name = $_POST['name'];
    $step = $_POST['step'];
    $about = $_POST['about'];
    $deadline = $_POST['deadline'];
    $report = $_POST['report'];
    $attachment = $_POST['attachment'];
    $groupId = $_POST['group_id']; // Make sure you have this value in your form
    $courseID = $_POST['course_id'];

    // Validate the input (you may need to add more validation)
    if (empty($name) || empty($step) || empty($about) || empty($deadline) || empty($report) || empty($attachment) || empty($groupId)) {
        $error_message = "All fields are required.";
    } else {
        // Insert the new task into the database
        $insertQuery = "INSERT INTO tasks (name, step, about, deadline, report, attachment, group_id, status) 
                        VALUES ('$name', '$step', '$about', '$deadline', '$report', '$attachment', '$groupId', 'processing')";

        if ($conn->query($insertQuery) === TRUE) {
            $success_message = "Task created successfully.";
            header("Location: stu_groups.php?course_id=$courseID");
        } else {
            $error_message = "Error creating task: " . $conn->error;
        }
    }
}

// Fetch the groups the student belongs to
$userEmail = $_SESSION['user_email'];
$groupsQuery = "SELECT group_id FROM group_members WHERE user_email = '$userEmail'";
$groupsResult = $conn->query($groupsQuery);

// Fetch the group details
$groups = array();
while ($group = $groupsResult->fetch_assoc()) {
    $groupId = $group['group_id'];
    $groupDetailsQuery = "SELECT * FROM groups WHERE id = $groupId";
    $groupDetailsResult = $conn->query($groupDetailsQuery);
    $groupDetails = $groupDetailsResult->fetch_assoc();
    $groups[] = $groupDetails;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Task</title>
    <!-- Include Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
        }

        .container {
            margin-top: 50px;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            color: #007bff;
        }

        form {
            max-width: 600px;
            margin: 0 auto;
        }

        label {
            font-weight: bold;
        }

        textarea {
            width: 100%;
            height: 100px;
        }

        button {
            background-color: #007bff;
            color: #ffffff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <h2>Create Task</h2>
        <?php
        // Display success or error messages
        if (isset($success_message)) {
            echo "<p style='color: green;'>$success_message</p>";
        } elseif (isset($error_message)) {
            echo "<p style='color: red;'>$error_message</p>";
        }
        ?>
        <form action="create_task.php" method="post">
            
        <input type="hidden" name="group_id" value="<?php echo $_GET['group_id']; ?>">
        <input type="hidden" name="course_id" value="<?php echo $_GET['course_id']; ?>">

            <div class="form-group">
                <label for="name">Task Name:</label>
                <input type="text" class="form-control" name="name" required>
            </div>
            <div class="form-group">
                <label for="step">Step:</label>
                <input type="text" class="form-control" name="step" required>
            </div>
            <div class="form-group">
                <label for="about">About:</label>
                <textarea class="form-control" name="about" rows="4" required></textarea>
            </div>
            <div class="form-group">
                <label for="deadline">Deadline:</label>
                <input type="datetime-local" class="form-control" name="deadline" required>
            </div>
            <div class="form-group">
                <label for="report">Report:</label>
                <textarea class="form-control" name="report" rows="4" required></textarea>
            </div>
            <div class="form-group">
                <label for="attachment">Attachment:</label>
                <input type="text" class="form-control" name="attachment" required>
            </div>
            <button type="submit" class="btn btn-primary">Create Task</button>
        </form>
    </div>

    <!-- Include Bootstrap JS and Popper.js -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
