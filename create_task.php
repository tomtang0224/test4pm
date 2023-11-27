<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// Include your database connection
include("db_connection.php"); // Replace with your actual connection file
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "project_management_system"; 

$conn = new mysqli($servername, $username, $password, $dbname);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $user_id = $_SESSION['user_id']; // Get the user ID from the session
    $task_name = $_POST['task_name'];
    $step = $_POST['step'];
    $about = $_POST['about'];
    $deadline = $_POST['deadline'];
    $report = $_POST['report'];
    $attachments = $_POST['attachments'];
    $status = $_POST['status'];

    // Insert the new task into the database
    $insertSql = "INSERT INTO tasks (user_id, name, step, about, deadline, report, attachments, status) VALUES ('$user_id', '$task_name', '$step', '$about', '$deadline', '$report', '$attachments', '$status')";

    if ($conn->query($insertSql) === TRUE) {
        $message = "Task added successfully";
    } else {
        $error = "Error adding task: " . $conn->error;
    }
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Task</title>
    <!-- Add any necessary CSS or Bootstrap links here -->
</head>
<body>

<!-- Display Success or Error Message -->
<div class="container">
    <?php if (isset($message)) : ?>
        <div class="alert alert-success" role="alert">
            <?php echo $message; ?>
        </div>
    <?php endif; ?>

    <?php if (isset($error)) : ?>
        <div class="alert alert-danger" role="alert">
            <?php echo $error; ?>
        </div>
    <?php endif; ?>
</div>

</body>
</html>

<?php
// Redirect back to the dashboard after a delay
header("refresh:2;url=admin_dashboard.php");
exit();
?>
