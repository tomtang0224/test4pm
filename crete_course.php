<?php
session_start();
include('db_connection.php');

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $course_name = $_POST['course_name'];
    $username = $_SESSION['username'];
    $user_id = $_SESSION['user_id'];

    // Add additional logic to calculate the number of groups for the course
    // For example, you might have a form field for specifying the number of groups.

    // Insert the new course into the database
    $insertSql = "INSERT INTO courses (course_name, username, user_id) VALUES ('$course_name', '$username', '$user_id')";

    if ($conn->query($insertSql) === TRUE) {
        echo "Course added successfully";
    } else {
        echo "Error adding course: " . $conn->error;
    }
}

// Redirect back to the dashboard or another page
header("Location: dashboard.php");
exit();
?>
