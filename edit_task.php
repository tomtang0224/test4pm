<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

include('db_connection.php');

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id'])) {
    $taskId = $_GET['id'];

    // Fetch task information
    $sql = "SELECT * FROM tasks WHERE id = $taskId";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $task = $result->fetch_assoc();
    } else {
        // Redirect to the dashboard if the task is not found
        header("Location: admin_dashboard.php");
        exit();
    }
} else {
    // Redirect to the dashboard if the ID is not provided
    header("Location: admin_dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Task</title>
</head>
<body>

    <h2>Edit Task</h2>



<form action="update_task.php" method="post">
    <input type="hidden" name="task_id" value="<?php echo $task['id']; ?>">
    <label for="task_name">Task Name:</label>
    <input type="text" name="task_name" value="<?php echo $task['name']; ?>" required>
    <label for="step">Step:</label>
    <input type="text" name="step" value="<?php echo $task['step']; ?>" required>
    <label for="about">About this step:</label>
    <textarea name="about"><?php echo $task['about']; ?></textarea>
    <label for="deadline">Step deadline:</label>
    <input type="datetime-local" name="deadline" value="<?php echo date('Y-m-d\TH:i', strtotime($task['deadline'])); ?>" required>
    <label for="report">Step report:</label>
    <textarea name="report"><?php echo $task['report']; ?></textarea>
    <label for="attachments">Attachments:</label>
    <input type="text" name="attachments" value="<?php echo $task['attachments']; ?>">
    <label for="status">Task Status:</label>
    <select name="status">
        <option value="processing" <?php echo ($task['status'] === 'processing') ? 'selected' : ''; ?>>Processing</option>
        <option value="finished" <?php echo ($task['status'] === 'finished') ? 'selected' : ''; ?>>Finished</option>
    </select>
    <button type="submit">Update Task</button>
</form>




</body>
</html>
