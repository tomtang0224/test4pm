<?php
session_start();
include('db_connection.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id'])) {
    $taskId = $_GET['id'];

    // Delete task from the database
    $deleteSql = "DELETE FROM tasks WHERE id = $taskId";

    if ($conn->query($deleteSql) === TRUE) {
        echo "Task deleted successfully";
    } else {
        echo "Error deleting task: " . $conn->error;
    }
}

// Redirect back to the dashboard
header("Location: redirect.php");
exit();
?>
