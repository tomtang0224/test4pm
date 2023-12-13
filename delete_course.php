<?php
// Include your database connection
include_once("db_connection.php");

// Check if the user is logged in as an admin
session_start();
if (!isset($_SESSION['user_email']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}

// Check if the course ID is provided in the URL
if (isset($_GET['id'])) {
    $courseId = $_GET['id'];

    // Perform deletion
    $deleteSql = "DELETE FROM courses WHERE course_id = '$courseId'";
    if ($conn->query($deleteSql) === TRUE) {
        // Successful deletion
        $_SESSION['success_message'] = "Course deleted successfully.";
    } else {
        // Error in deletion
        $_SESSION['error_message'] = "Error deleting course: " . $conn->error;
    }

    // Redirect back to the Edit Courses page
    header("Location: edit_courses.php");
    exit();
} else {
    // Redirect if course ID is not provided
    header("Location: edit_courses.php");
    exit();
}
?>
