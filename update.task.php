<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $taskId = $_POST['task_id'];
    $task_name = $_POST['task_name'];
    $step = $_POST['step'];
    $about = $_POST['about'];
    $deadline = $_POST['deadline'];
    $report = $_POST['report'];
    $attachments = $_POST['attachments'];

    // Update task information
    $updateSql = "UPDATE tasks SET name='$task_name', step='$step', about='$about', deadline='$deadline', report='$report', attachments='$attachments' WHERE id=$taskId";
    
    if ($conn->query($updateSql) === TRUE) {
        echo "Task updated successfully";
    } else {
        echo "Error updating task: " . $conn->error;
    }
}

// Redirect back to the dashboard
header("Location: dashboard.php");
exit();
?>
