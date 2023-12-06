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
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Get user input
        $groupId = $_POST['group_id'];
        $groupName = $_POST['group_name'];
        $courseName = $_POST['course_name'];
        $size = $_POST['size'];
        $groupMember = $_POST['group_member'];
    
        // Validate input (add more validation as needed)
        if (empty($groupName) || empty($courseName) || empty($size) || empty($groupMember)) {
            echo "Invalid input. Please provide valid values for all fields.";
        } else {
            include_once("db_connection.php"); // Include your database connection code
    
            // Assume you have a 'groups' table with columns: id, name, course_name, size, group_member
            $tableName = "groups";
    
            // Update the group in the database
            $updateQuery = "UPDATE $tableName SET name='$groupName', course_name='$courseName', size='$size', group_member='$groupMember' WHERE id='$groupId'";
    
            if ($conn->query($updateQuery) === TRUE) {
                echo "Group updated successfully.";
                header("refresh:2;url=admin_dashboard.php");
                exit();
            } else {
                echo "Error updating group: " . $conn->error;
            }
    
            // Close the database connection
            $conn->close();
        }
    }
?>
