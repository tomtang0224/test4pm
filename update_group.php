<?php
    // Include your database connection
    include_once("db_connection.php");

    // Check if the user is logged in as an admin
    session_start();
    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
        header("Location: index.php");
        exit();
    }

    // Check if the form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $groupId = $_POST['group_id'];
        $groupName = $_POST['group_name'];
        $courseName = $_POST['course_name'];
        $size = $_POST['size'];

        // Update the group details in the database
        $updateSql = "UPDATE groups SET name='$groupName', course_name='$courseName', size=$size WHERE id=$groupId";

        if ($conn->query($updateSql) === TRUE) {
            echo "Group updated successfully";
        } else {
            echo "Error updating group: " . $conn->error;
        }
    }

    // Redirect back to the edit group form
    header("Location: redirect.php");
    exit();
?>
