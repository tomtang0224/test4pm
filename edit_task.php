<?php
session_start();

if (!isset($_SESSION['user_email'])) {
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
    <!-- Include Bootstrap CSS for styling -->
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

    <div class="container">
        <h2>Edit Task</h2>

        <form action="update_task.php" method="post">
            <input type="hidden" name="task_id" value="<?php echo $task['id']; ?>">

            <div class="form-group">
                <label for="task_name">Task Name:</label>
                <input type="text" class="form-control" name="task_name" value="<?php echo $task['name']; ?>" required>
            </div>

            <div class="form-group">
                <label for="step">Step:</label>
                <input type="text" class="form-control" name="step" value="<?php echo $task['step']; ?>" required>
            </div>

            <div class="form-group">
                <label for="about">About this step:</label>
                <textarea class="form-control" name="about"><?php echo $task['about']; ?></textarea>
            </div>

            <div class="form-group">
                <label for="deadline">Step deadline:</label>
                <input type="datetime-local" class="form-control" name="deadline" value="<?php echo date('Y-m-d\TH:i', strtotime($task['deadline'])); ?>" required>
            </div>

            <div class="form-group">
                <label for="report">Step report:</label>
                <textarea class="form-control" name="report"><?php echo $task['report']; ?></textarea>
            </div>

            <div class="form-group">
                <label for="attachments">Attachments:</label>
                <input type="text" class="form-control" name="attachments" value="<?php echo $task['attachment']; ?>">
            </div>

            <div class="form-group">
                <label for="status">Task Status:</label>
                <select class="form-control" name="status">
                    <option value="processing" <?php echo ($task['status'] === 'processing') ? 'selected' : ''; ?>>Processing</option>
                    <option value="finished" <?php echo ($task['status'] === 'finished') ? 'selected' : ''; ?>>Finished</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Update Task</button>
        </form>
    </div>

</body>

</html>
